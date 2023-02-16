<div>
    <div class="container" style="padding: 30px 0 ">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Update Profile
                </div>
                <div class="panel-body">
                    @if(Session::has('message'))
                        <div class="alert alert-success" role="alert">{{Session::get('message')}}</div>
                    @endif
                    <form wire:submit.prevent="updateProfile">
                        <div class="col-md-4">
                            @if($newimage)
                                <img src="{{$newimage->temporaryUrl()}}" width="100%" alt="">
                            @elseif($image)
                                <img src="{{asset('assets/images/profile')}}/{{$image}}" width="100%">
                            @else
                                <img src="{{asset('assets/images/profile/default.png')}}" width="100%">
                            @endif
                            <input type="file" class="form-control" wire:model="newimage">
                        </div>
                        <div class="col-md-8">
                            <p><b>Name: </b><input type="text" class="form-control" wire:model="name"></p>
                            <p><b>Lastname: </b><input type="text" class="form-control" wire:model="lastname"></p>
                            <p><b>Email: </b>{{$email}}</p>
                            <p><b>Phone: </b><input type="text" class="form-control" wire:model="mobile"></p>
                            <hr>
                            <p><b>Province: </b><select name="province" class="form-control" wire:model="province" wire:change="changeCity">
                                    <option value="">--Provinsi--</option>
                                    @foreach($provinces as $province=>$value)
                                    <option value="{{$province}}">{{$value}}</option>
                                    @endforeach
                                </select></p>
                            <p><b>City: </b><select name="city" class="form-control" wire:model="city">
                                    <option value="">--Kota--</option>
                                    @foreach($cities as $city)
                                    <option value="{{$city->id}}">{{$city->title}}</option>
                                    @endforeach  
                                </select>   </p>
                                
                            <p><b>Country: </b><input type="text" class="form-control" wire:model="country"></p>
                            <p><b>Zipcode: </b><input type="text" class="form-control" wire:model="zipcode"></p>
                            <hr>
                            <p><b>Line1: </b><input type="text" class="form-control" wire:model="line1"></p>
                            <p><b>Line2: </b><input type="text" class="form-control" wire:model="line2"></p>
                            
                            
                            <button type="submit" class="btn btn-info pull-right">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>