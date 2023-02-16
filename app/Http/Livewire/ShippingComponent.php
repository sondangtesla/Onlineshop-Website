<?php

namespace App\Http\Livewire;

use App\Models\City;
use App\Models\Province;

use App\Models\Courier;
use Illuminate\Http\Client\Request;
use Kavist\RajaOngkir\Facades\RajaOngkir;
use Livewire\Component;



class ShippingComponent extends Component
{
    public $province_origin;
    public $city_origin;
    public $province_destination;
    public $city_destination;
    public $city_id;
    public $weight;
    public $courier;
    public $nama_jasa;
    public $result = [];


    // public function getCities($city_id)
    // {
    //     $city = City::where('province_id',$city_id)->pluck('title','city_id');
    //     return json_encode($city);

    // }

    public function submit()
    {
        $this->validate([
            'province_origin' => 'required',
            'city_origin' => 'required',
            'province_destination' => 'required',
            'city_destination' => 'required',
            'courier' => 'required',
            'weight' => 'required'
        ]);

        $cost = RajaOngkir::ongkosKirim([
            'origin' => $this->city_origin,
            'destination' => $this->city_destination,
            'weight' => $this->weight,
            'courier' => $this->courier,
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
        return $this->result;
    }
    public function changeCity()
    {
        $this->city_id = 0;
    }

    public function save_ongkir($biaya)
    {
        return $biaya;

    }
    public function render()
    {
        
        $couriers = Courier::pluck('title','code');
        $provinces = Province::pluck('title', 'province_id');
        $cities_origin = City::where('province_id', $this->province_origin)->get();
        $cities_destination = City::where('province_id', $this->province_destination)->get();
        return view('livewire.shipping-component',['couriers'=>$couriers, 'provinces'=>$provinces, 'cities_origin'=>$cities_origin, 'cities_destination'=>$cities_destination])->layout('layouts.base');
        
    }
}
