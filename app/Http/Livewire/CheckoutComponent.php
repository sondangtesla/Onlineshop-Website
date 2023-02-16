<?php

namespace App\Http\Livewire;

use App\Mail\OrderMail;
use App\Models\City;
use App\Models\Coba;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Product;
use App\Models\Province;
use App\Models\Shipping;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Mail;
use Stripe;

class CheckoutComponent extends Component
{
    
    public $ship_to_different;
    
    public $firstname;
    public $lastname;
    public $email;
    public $mobile;
    public $line1;
    public $line2;
    public $city;
    public $province;
    public $country;
    public $zipcode;

    public $s_firstname;
    public $s_lastname;
    public $s_email;
    public $s_mobile;
    public $s_line1;
    public $s_line2;
    public $s_city;
    public $s_province;
    public $s_country;
    public $s_zipcode;

    public $paymentmode;
    public $thankyou;

    public $card_no;
    public $exp_month;
    public $exp_year;
    public $cvc;

    public $status_payment;
    public $result_pay;
    public $item_details=[];
    
    public function mount()
    {
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;
        
        $user = User::find(Auth::user()->id);
        
        $this->firstname = $user->name;
        $this->lastname = $user->profile->lastname;
        $this->email = $user->email;
        $this->mobile = $user->profile->mobile;
        $this->line1 = $user->profile->line1;
        $this->line2 = $user->profile->line2;
        $this->city = City::where('city_id', $user->profile->city)->pluck('title');
        $this->province = Province::where('province_id', $user->profile->province)->pluck('title');
        $this->country = $user->profile->country;
        $this->zipcode = $user->profile->zipcode;

        if(isset($_GET['result_data']))
        {
            
            $pay_json = json_decode($_GET['result_data'],true);
            $this->makeTrans($pay_json);
        }
        
    }

    public function updated($fields)
    {
        $this ->validateOnly($fields,[
            'firstname'=>'required',
            'lastname'=>'required',
            'email'=>'required|email',
            'mobile'=>'required|numeric',
            'line1'=>'required',
            'city'=>'required',
            'province'=>'required',
            'country'=>'required',
            'zipcode'=>'required',
            'paymentmode' => 'required'
        ]);

        if($this->ship_to_different)
        {
            $this->validateOnly($fields,[
                's_firstname'=>'required',
                's_lastname'=>'required',
                's_email'=>'required|email',
                's_mobile'=>'required|numeric',
                's_line1'=>'required',
                's_city'=>'required',
                's_province'=>'required',
                's_country'=>'required',
                's_zipcode'=>'required'
            ]);
        }

        if($this->paymentmode == 'card')
        {
            $this->validateOnly($fields,[
                'card_no'=>'required|numeric',
                'exp_month'=>'required|numeric',
                'exp_year'=>'required|numeric',
                'cvc'=>'required|numeric'
            ]);
        }

    }

   
    
    public function placeOrder()
    {
        $order = new Order();
        $order->user_id = Auth::user()->id;
        $order->subtotal = (int)session() -> get('checkout')['subtotal'];
        $order->discount = session() -> get('checkout')['discount'];
        $order->tax = (int)session() -> get('checkout')['tax'];
        $order->ongkir = (int)session() -> get('checkout')['ongkir'];
        $order->total = (int)session() -> get('checkout')['total'];

        $order->firstname = $this -> firstname;
        $order->lastname = $this -> lastname;
        $order->email = $this -> email;
        $order->mobile = $this -> mobile;
        $order->line1 = $this -> line1;
        $order->line2 = $this -> line2;
        $order->city = $this -> city[0];
        $order->province = $this -> province[0];
        $order->country = $this -> country;
        $order->zipcode = $this -> zipcode;
        $order->status = 'ordered';
        $order->is_shipping_different = $this ->ship_to_different ? 1:0;
        $order->save();


        foreach(Cart::instance('cart')->content() as $item)
        {
            $orderItem = new OrderItem();

            $orderItem->product_id = $item->id;
            $orderItem->order_id = $order->id;
            $orderItem->price = $item->price;
            $orderItem->quantity = $item->qty;
            if($item->options)
            {
                $orderItem->options = serialize($item->options);
            }
            $orderItem->save();
            
            
        }
        $this->sendOrderConfirmationMail($order);
        
        return $order->id;
    }

    public function getSnapToken($order)
    {
        foreach(Cart::instance('cart')->content() as $item)
        {
                      
            $this->item_details[]=array(
                    "id"=> $item->id,
                    "price"=> (int)$item->price,
                    "quantity"=> $item->qty,
                    "name"=> Product::where('id', $item->id)->pluck('name')[0]
            );
            
        }
        //tambahkan ongkir
        array_push($this->item_details,[
            "id" => "OGK001",
            "price" => (int)session() -> get('checkout')['ongkir'],
            "quantity" => 1,
            "name" => "biaya pengiriman"
        ]);

        //tambahkan pajak
        array_push($this->item_details,[
            "id" => "TAX001",
            "price" => (int)session() -> get('checkout')['tax'],
            "quantity" => 1,
            "name" => "biaya pajak"
        ]);
        
        $params = array(
            'transaction_details' => array(
                'order_id' => $order,
                'gross_amount' => (int)session() -> get('checkout')['total'],
            ),
            'item_details' => $this->item_details,
            'customer_details' => array(
                'first_name' => $this -> firstname,
                'last_name' => $this -> lastname,
                'email' => $this->email,
                'phone' => $this->mobile,
                
            ),
        );
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return $snapToken;
    }

    public function makeTrans($pay_json)
    {
        $transaction = new Transaction();
        $transaction->user_id = Auth::user()->id;
        $transaction->order_id = $pay_json['order_id'];
        $transaction->status_code = $pay_json['status_code'];
        $transaction->status_message = $pay_json['status_message'];
        $transaction->transaction_id = $pay_json['transaction_id'];
        $transaction->gross_amount = $pay_json['gross_amount'];
        $transaction->payment_type = $pay_json['payment_type'];
        $transaction->transaction_time = $pay_json['transaction_time'];
        $transaction->transaction_status = $pay_json['transaction_status'];
        $transaction->payment_code = isset($pay_json['payment_code']) ? $pay_json['payment_code'] : null;
        $transaction->pdf_url = isset($pay_json['pdf_url']) ? $pay_json['pdf_url'] : null;
        $transaction->finish_redirect_url = $pay_json['finish_redirect_url'];
        $transaction->save();
        
    }
    public function resetCart()
    {
        $this->thankyou = 1;
        Cart::instance('cart')->destroy();
        session()->forget('checkout');
    }
    

    public function sendOrderConfirmationMail($order)
    {
        Mail::to($order->email)->send(new OrderMail($order));
    }

    public function verifyForCheckout()
    {
        if(!Auth::check())
        {
            return redirect()->route('login');
        }
        else if($this->thankyou)
        {
            return redirect()->route('thankyou');
        }
        else if(!session()->get('checkout'))
        {
            return redirect()->route('product.cart');
        }
    }

    public function render()
    {
        $most_view_products = Product::inRandomOrder()->limit(8)->get();
        $order_id = $this->placeOrder();
        $snapToken = $this->getSnapToken($order_id);
        $this->verifyForCheckout();
        return view('livewire.checkout-component',['most_view_products'=>$most_view_products,'snapToken'=>$snapToken, 'result_pay'=>$this->result_pay])->layout('layouts.base');
    }
}
