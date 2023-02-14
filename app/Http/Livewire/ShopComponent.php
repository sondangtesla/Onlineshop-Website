<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class ShopComponent extends Component
{
    public $sorting;
    public $pagesize;
    public $min_price;
    public $max_price;


    use WithPagination;

    public function mount()
    {
        $this->sorting = "default";
        $this->pagesize = 12;

        $this->min_price=10000;
        $this->max_price=500000;
    }
    public function store($product_id, $product_name,$product_price)
    {
        Cart::instance('cart')->add($product_id,$product_name,1,$product_price)->associate('App\Models\Product');
        $this->emitTo('cart-count-component','refreshComponent');
        session()->flash('success_message', ' Item added in cart');
        
        
        
    }
    
    public function addToWishList($product_id, $product_name,$product_price)
    {
        Cart::instance('wishlist')->add($product_id, $product_name,1,$product_price)->associate('App\Models\Product');
        $this->emitTo('wishlist-count-component','refreshComponent');
    }

    public function removeFromWishlist($product_id)
    {
        foreach(Cart::instance('wishlist')->content() as $witem);
        {
            if($witem->id == $product_id)
            {
                Cart::instance('wishlist')->remove($witem->rowId);
                $this->emitTo('wishlist-count-component','refreshComponent');
                return;
            }
        }
    }
    public function render()
    {
        
        if($this->sorting=='date')
        {
            $products = Product::whereBetween('regular_price', [$this->min_price, $this->max_price])->orderBy('created_at', 'DESC')->paginate($this->pagesize);
        }

        else if($this->sorting=="price")
        {
            $products = Product::whereBetween('regular_price', [$this->min_price, $this->max_price])->orderBy('regular_price', 'ASC')->paginate($this->pagesize);
        }

        else if($this->sorting=="price_desc")
        {
            $products = Product::whereBetween('regular_price', [$this->min_price, $this->max_price])->orderBy('regular_price', 'DESC')->paginate($this->pagesize);
        }

        else
        {

            $products = Product::whereBetween('regular_price', [$this->min_price, $this->max_price])->paginate($this->pagesize);
        }

        $popular_products = Product::inRandomOrder()->limit(4)->get();
        $categories = Category::all();

        if(Auth::check())
        {
            Cart::instance('cart')->store(Auth::user()->email);
            Cart::instance('wishlist')->store(Auth::user()->email);
        }
        return view('livewire.shop-component', ['products'=>$products, 'popular_products'=>$popular_products, 'categories'=>$categories])->layout('layouts.base');
    }
}
