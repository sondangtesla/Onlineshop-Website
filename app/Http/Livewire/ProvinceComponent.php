<?php

namespace App\Http\Livewire;

use App\Models\City;
use Livewire\Component;

class ProvinceComponent extends Component
{
    
    public function render($id)
    {
        $city = City::where('province_id',$id)->pluck('title','city_id');
        return json_encode($city);
    }
}
