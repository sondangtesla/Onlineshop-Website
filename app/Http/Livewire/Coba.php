<?php

namespace App\Http\Livewire;

use App\Models\Coba as ModelsCoba;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class Coba extends Component
{
    public $status_payment;
    
    

   
    public function mount()
    {
        // if(Auth::user())
        // {
        //     return redirect()->route('login');
            
        // }
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;
        
        if(isset($_GET['result_data']))
        {
            
            $pay_json = json_decode($_GET['result_data'],true);
            $coba = new ModelsCoba();
            $coba->status_code = $pay_json['status_code'];
            $coba->status_message = $pay_json['status_message'];
            $coba->transaction_id = $pay_json['transaction_id'];
            $coba->order_id = $pay_json['order_id'];
            $coba->gross_amount = $pay_json['gross_amount'];
            $coba->payment_type = $pay_json['payment_type'];
            $coba->transaction_time = $pay_json['transaction_time'];
            $coba->transaction_status = $pay_json['transaction_status'];
            $coba->payment_code = isset($pay_json['payment_code']) ? $pay_json['payment_code'] : null;
            $coba->pdf_url = isset($pay_json['pdf_url']) ? $pay_json['pdf_url'] : null;
            $coba->finish_redirect_url = $pay_json['finish_redirect_url'];
            $coba->save();
        }
        //ambil data belanja disini
        // $this->belanja = Belanja::find($id);
        $params = array(
            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => 10000,
            ),
            'item_details' => array(
                [
                    "id"=> "ITEM1",
                    "price"=> 10000,
                    "quantity"=> 1,
                    "name"=> "Midtrans Bear",
                    "brand"=> "Midtrans",
                    "category"=> "Toys",
                    "merchant_name"=> "Midtrans",
                    "url"=> "http://toko/toko1?item=abc"
                  ],
                  [
                    "id"=> "ITEM2",
                    "price"=> 10000,
                    "quantity"=> 1,
                    "name"=> "Midtrans Bea2r",
                    "brand"=> "Midtrans",
                    "category"=> "Toys",
                    "merchant_name"=> "Midtrans",
                    "url"=> "http://toko/toko1?item=abc"
                  ]
            ),
            'customer_details' => array(
                'first_name' => 'budi',
                'last_name' => 'pratama',
                'email' => 'budi.pra@example.com',
                'phone' => '08111222333',
                
            ),
        );
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $this->snapToken = $snapToken;
    }
    public function render()
    {
        
        
        
        
        
        return view('livewire.coba', ['snapToken'=>$this->snapToken])->layout('layouts.base');
    }

    
}
