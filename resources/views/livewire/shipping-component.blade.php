<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card-header">
                Cek Ongkir
            </div>
            <div class="card-body">
                @if(Session::has('message'))
                    <div class="alert alert-success" role="alert">
                        {{Session::get('message')}}
                    </div>
                @endif
                <form class="form-horizontal" wire:submit.prevent="submit">
                    {{csrf_field()}}
                    <div class="form-group-sm">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Provinsi Asal</label>
                                <select name="province_origin" class="form-control" wire:model="province_origin" wire:change="changeCity">
                                    <option value="">--Provinsi--</option>
                                    @foreach($provinces as $province=>$value)
                                    <option value="{{$province}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Kota Asal</label>
                                <select name="city_origin" class="form-control" wire:model="city_origin">
                                    <option value="0">--Kota--</option>
                                    @foreach($cities_origin as $city)
                                    <option value="{{$city->id}}">{{$city->title}}</option>
                                    @endforeach  
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Provinsi Tujuan</label>
                                <select name="province_destination" class="form-control" wire:model="province_destination" wire:change="changeCity">
                                    <option value="">--Provinsi--</option>
                                    @foreach($provinces as $province=>$value)
                                    <option value="{{$province}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Kota Tujuan</label>
                                <select name="city_destination" class="form-control" wire:model="city_destination">
                                    <option value="">--Kota--</option>
                                    @foreach($cities_destination as $city)
                                    <option value="{{$city->id}}">{{$city->title}}</option>
                                    @endforeach  
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Kurir</label>
                                <select name="courier" class="form-control" wire:model="courier">
                                    <option value="">--Kurir--</option>
                                    @foreach($couriers as $courier => $value)
                                    <option value="{{$courier}}">{{$value}}</option>
                                    @endforeach  
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Berat (g)</label>
                                <input type="number" name="weight" id="" class="form-control" value="1000" wire:model="weight">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-md-4 control-label"></label>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
                @if($result)
                    <section class="products mb-5">
                        <div class="row mt-4">
                            @foreach($result as $r)
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <div><h4>{{$nama_jasa}}</h4></div>
                                            <div class="row mt-2">
                                                <div class="col-md-12">
                                                    <h5><strong>{{$r['biaya']}}</strong></h5>
                                                    <h6><strong>{{$r['etd']}}</strong></h6>
                                                    <h6><strong>{{$r['description']}}</strong></h6>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <button class="btn btn-success btn-block" wire:click="save_ongkir({{ $r['biaya'] }})">Tambahkan Sebagai Ongkir</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </section>
                @endif


                
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Bundle with Popper
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('select[name = "province_origin"]').on('change', function(){
            let provinceId = $(this).val();
            if(provinceId) {
                jQuery.ajax({
                    url: '/province/'+provinceId+'/cities',
                    type : "GET",
                    dataType : "json",
                    success : function(data) {
                        $('select[name = "city_origin"]').empty();
                        $.each(data, function(key, value){
                            $('select[name = "city_origin"]').append('<option value="'+key+'">'+value+'</option>');
                        });
                    },
                });
            } else {
                $('select[name = "city_origin"]').empty();
            }
        });

        $('select[name = "province_destination"]').on('change', function(){
            let provinceId = $(this).val();
            if(provinceId) {
                jQuery.ajax({
                    url: '/province/'+provinceId+'/cities',
                    type : "GET",
                    dataType : "json",
                    success : function(data) {
                        $('select[name = "city_destination"]').empty();
                        $.each(data, function(key, value){
                            $('select[name = "city_destination"]').append('<option value="'+key+'">'+value+'</option>');
                        });
                    },
                });
            } else {
                $('select[name = "city_destination"]').empty();
            }
        });
    })
</script> -->