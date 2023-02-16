<?php

namespace App\Http\Livewire;

use App\Models\City;
use Livewire\Component;
use App\Models\Sale;
use App\Models\Coupon;
use App\Models\Province;
use App\Models\User;
use Cart;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use RajaOngkir;


class CartComponent extends Component

{
    public $haveCouponCode;
    public $couponCode;
    public $discount;
    public $subtotalAfterDiscount;
    public $taxAfterDiscount;
    public $totalAfterDiscount;
    public $nama_jasa;
    public $ongkir;
    public $description;
    public $estimasi;
    public $totalAfterOngkir;
    public $result = [];

    public function mount()
    {   if(Auth::user()->utype === 'USR')
        {

            $user = User::find(Auth::user()->id);
    
            $this->city = City::where('city_id', $user->profile->city)->pluck('title');
            $this->province = Province::where('province_id', $user->profile->province)->pluck('title');
            
            $cost = RajaOngkir::ongkosKirim([
                'origin' => 457,
                'destination' => Auth::user()->profile->city,
                'weight' => 500,
                'courier' => 'tiki',
            ])->get();
            
            $this->nama_jasa = $cost[0]['name'];
    
            foreach($cost[0]['costs'] as $row)
            {
                $this->result[]=array(
                    'description' => $row['description'],
                    'biaya' => $row['cost'][0]['value'],
                    'etd' => $row['cost'][0]['etd']
                );
            }
        }
        else
        {
            return redirect()->route('login');
        }
    }

    public function increaseQuantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId,$qty);
        $this->emitTo('cart-count-component','refreshComponent');
    }

    public function decreaseQuantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId,$qty);
        $this->emitTo('cart-count-component','refreshComponent');
    }

    public function destroy($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        $this->emitTo('cart-count-component','refreshComponent');
        session()->flash('success_message', ' Item has been  removed');
    }

    public function destroyAll()
    {
        Cart::instance('cart')->destroy();
        $this->emitTo('cart-count-component','refreshComponent');
        session()->flash('success_message', ' All item has been  removed');
    }

    public function switchToSaveForLater($rowId)
    {
        $item = Cart::instance('cart')->get($rowId);
        Cart::instance('cart')->remove($rowId);
        Cart::instance('saveForLater')->add($item->id, $item->name,1,$item->price)->associate('App\Models\Product');
        $this->emitTo('cart-count-component','refreshComponent');
        session()->flash('success_message', ' Item has been saved for later');
    }


    public function moveToCart($rowId)
    {
        $item = Cart::instance('saveForLater')->get($rowId);
        Cart::instance('saveForLater')->remove($rowId);
        Cart::instance('cart')->add($item->id, $item->name,1,$item->price)->associate('App\Models\Product');
        $this->emitTo('cart-count-component','refreshComponent');
        session()->flash('s_success_message', ' Item has been moved to cart');
    }

    public function deleteFromSaveForLater($rowId)
    {
        Cart::instance('saveForLater')->remove($rowId);
        session()->flash('s_success_message', ' Item has been removed from saved for later');
    }

    public function applyCouponCode()
    {
        $coupon = Coupon::where('code', $this->couponCode)->where('expiry_date','>=', Carbon::today())->where('cart_value','<=',Cart::instance('cart')->subtotal())->first();
        if(!$coupon)
        {
            session()->flash('coupon_message','Coupon code is invalid!');
            return;
        }

        session()->put('coupon',[
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'cart_value' => $coupon->cart_value
        ]);
    }

    public function calculateDiscounts()
    {
        if(session()->has('coupon'))
        {
            if(session()->get('coupon')['type'] == 'fixed')
            {
                $this->discount = session() -> get('coupon')['value'];
            }

            else
            {
                $this->discount = (Cart::instance('cart')->subtotal * session()->get('coupon')['value'])/100;
            }
            $this->subtotalAfterDiscount = Cart::instance('cart')->subtotal() - $this->discount;
            $this->taxAfterDiscount = ($this->subtotalAfterDiscount * config('cart.tax'))/100;
            $this->totalAfterDiscount = $this->subtotalAfterDiscount + $this->taxAfterDiscount;
        }
    }

    public function removeCoupon()
    {
        session()->forget('coupon');
    }

    public function checkout()
    {
        if(Auth::check())
        {
            return redirect()->route('checkout');
        }
        else
        {
            return redirect()->route('login');
        }
    }

    

    public function setAmmountForCheckout()
    {
        
        $this->totalAfterOngkir = Cart::instance('cart')->total() + $this->ongkir;
        if(!Cart::instance('cart')->count() > 0)
        {
            session()->forget('checkout');
            return;
        }
        
        if(session()->has('coupon'))
        {
            session()->put('checkout',[
                'discount' => $this->discount,
                'subtotal' => $this->subtotalAfterDiscount,
                'tax' => $this->taxAfterDiscount,
                'ongkir' => $this->ongkir,
                'total' => $this->totalAfterDiscount
            ]);
        }

        else
        {
            session()->put('checkout',[
                'discount' => 0,
                'subtotal' => Cart::instance('cart')->subtotal(),
                'tax' => Cart::instance('cart')->tax(),
                'ongkir' => $this->ongkir,
                'total' => $this->totalAfterOngkir
            ]);
        }
    }

    

    public function render()
    {
        $sale = Sale::find(1);
        
        
        if(session()->has('coupon'))
        {
            if(Cart::instance('cart')->subtotal() < session()->get('coupon')['cart_value'])
            {
                
                session()->forget('coupon');
            }
            else
            {
                $this->calculateDiscounts();
            }
        }
        $this->setAmmountForCheckout();

        if(Auth::check())
        {
            Cart::instance('cart')->store(Auth::user()->email);
        }
        return view('livewire.cart-component',['sale'=>$sale])->layout('layouts.base');
    }
}
