<?php

namespace App\Http\Livewire\User;

use App\Models\City;
use App\Models\Profile;
use App\Models\Province;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserProfileComponent extends Component
{
   
    public function render()
    {
        $userProfile = Profile::where('user_id', Auth::user()->id)->first();
        if(!$userProfile)
        {
            $profile =  new Profile();
            $profile->user_id = Auth::user()->id;
            $profile->save();

        }
        $user = User::find(Auth::user()->id);
        $city = City::where('city_id', $user->profile->city)->pluck('title');
        $province = Province::where('province_id', $user->profile->province)->pluck('title');
        return view('livewire.user.user-profile-component',['user'=>$user, 'city'=>$city[0], 'province'=>$province[0]])->layout('layouts.base');
    }
}
