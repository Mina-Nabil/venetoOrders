@extends('layouts.app')

@section('content')



<div class="row">

    <div class="col-lg-12">
        <form class="form pt-3" method="post" action="{{ url($formURL) }}" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $formTitle }}</h4>
                    <h6 class="card-subtitle">Order Details</h6>

                    @csrf



                    <div class="form-group">
                        <label>Order Source</label>
                        <div class="input-group mb-3">
                            <select name=source class="select2 form-control custom-select" style="width: 100%; height:36px;">
                                <option value="" disabled selected>Pick From Order Sources</option>
                                @foreach($sources as $source)
                                <option value="{{ $source->id }}" @if(old('source')==$source->id)
                                    selected
                                    @endif
                                    >
                                    {{$source->ORSC_NAME}} - Account: {{$source->client_account->CLNT_NAME}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('user')}}</small>
                    </div>


                    <div class="form-group">
                        <label>Client Name</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Full Name" name=guestName value="{{ old('guestName')}}">
                        </div>
                        <small class="text-danger">{{$errors->first('guestName')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Client Mobile Number</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Client Mobile Number" name=guestMob value="{{ old('guestMob')}}">
                        </div>
                        <small class="text-danger">{{$errors->first('guestMob')}}</small>
                    </div>


                    <div class="form-group">
                        <label>Area</label>
                        <div class="input-group mb-3">
                            <select name=area id=areaSel class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                <option value="" disabled selected>Pick From Areas</option>
                                @foreach($areas as $area)
                                <option value="{{ $area->id }}" @if(old('area')==$area->id)
                                    selected
                                    @endif
                                    >{{$area->AREA_NAME}} : {{$area->AREA_ARBC_NAME}}</option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('area')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Payment Option</label>
                        <div class="input-group mb-3">
                            <select name=option class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                <option value="" disabled selected>Pick From Payment Options</option>
                                @foreach($payOptions as $option)
                                <option value="{{ $option->id }}" @if(old('option')==$option->id)
                                    selected
                                    @endif
                                    >{{$option->PYOP_NAME}}</option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('option')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Delivery Address</label>
                        <div class="input-group mb-3">
                            <textarea class="form-control" name="address" id=userAdrs rows="3" required>{{old('address')}}</textarea>
                        </div>
                        <small class="text-danger">{{$errors->first('address')}}</small>
                    </div>
                    <div class="form-group">
                        <label>Additional Notes</label>
                        <div class="input-group mb-3">
                            <textarea class="form-control" name="note" rows="3">{{old('note')}}</textarea>
                        </div>
                        <small class="text-danger">{{$errors->first('note')}}</small>
                    </div>


                </div>
            </div>
            <div class=card>
                <div class="card-body">
                    <h4 class="card-title">Order Items</h4>
                    <h6 class="card-subtitle">Pick from our inventory</h6>

                    <div class="row ">

                        <div id="dynamicContainer" class="nopadding row col-lg-12">
                        </div>

                        <div class="row col-lg-12">
                            <div class="col-lg-6">
                                <div class="input-group mb-2">
                                    <select name=item[] class="form-control select2  custom-select" style="width:100%"  required>
                                        <option disabled hidden selected value="">Model</option>
                                        @foreach($finished as $item)
                                        <option value="{{ $item->id }}">
                                            {{$item->BRND_NAME}} - {{$item->MODL_UNID}} - Price: {{$item->FNSH_PRCE}}EGP</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group mb-2">
                                    <select name=size[] class="form-control select2  custom-select" style="width:100%"  required>
                                        <option disabled hidden selected value="">Pick a Size</option>
                                        <option value="36">36</option>
                                        <option value="38">38</option>
                                        <option value="40">40</option>
                                        <option value="42">42</option>
                                        <option value="44">44</option>
                                        <option value="46">46</option>
                                        <option value="48">48</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-lg-3">
                                <div class="input-group mb-3">
                                    <input type="number" step=1 class="form-control amount" placeholder="Items Count" min=0 name=count[] aria-describedby="basic-addon11" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-success" id="dynamicAddButton" type="button" onclick="addToab();"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    @if($isCancel)
                    <a href="{{url($homeURL) }}" class="btn btn-dark">Cancel</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

</div>
@endsection

@section('js_content')
<script>
    var room = 1;
   function addToab() {
   
   room++;
   var objTo = document.getElementById('dynamicContainer')
   var divtest = document.createElement("div");
   divtest.setAttribute("class", "nopadding row col-lg-12 removeclass" + room);
   var rdiv = 'removeclass' + room;
   var concatString = "";
   concatString +=   '<div class="col-lg-6">\
                                <div class="input-group mb-2">\
                                    <select name=item[] class="form-control select2  custom-select" required>\
                                        <option disabled hidden selected value="">Model</option>\
                                        @foreach($finished as $item)\
                                        <option value="{{ $item->id }}">\
                                            {{$item->BRND_NAME}} - {{$item->MODL_UNID}} - Price: {{$item->FNSH_PRCE}}EGP</option>\
                                        @endforeach\
                                    </select>\
                                </div>\
                            </div>\
                            <div class="col-lg-3">\
                                <div class="input-group mb-2">\
                                    <select name=size[] class="form-control select2  custom-select" required>\
                                        <option disabled hidden selected value="">Pick a Size</option>\
                                        <option value="36">36</option>\
                                        <option value="38">38</option>\
                                        <option value="40">40</option>\
                                        <option value="42">42</option>\
                                        <option value="44">44</option>\
                                        <option value="46">46</option>\
                                        <option value="48">48</option>\
                                        <option value="50">50</option>\
                                    </select>\
                                </div>\
                            </div>';
   concatString +=                    " <div class='col-lg-3'>\
                               <div class='input-group mb-3'>\
                                   <input type='number' step=1 class='form-control amount' placeholder='Items Count' min=0 id=count" + room + "\
                                       name=count[] \
                                       aria-describedby='basic-addon11' required>\
                                   <div class='input-group-append'>\
                                    <button class='btn btn-danger' type='button' onclick='removeToab(" + room + ");'><i class='fa fa-minus'></i></button>\
                                   </div>\
                               </div>\
                           </div>";
   
   divtest.innerHTML = concatString;
   
   objTo.appendChild(divtest);
   $(".select2").select2()

   }

   function removeToab(rid) {
    $('.removeclass' + rid).remove();

    }
   
</script>
@endsection