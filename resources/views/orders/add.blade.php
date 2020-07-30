@extends('layouts.app')

@section('content')



<div class="row">

    <div class="col-lg-12">
        <form class="form pt-3" method="post" action="{{ url($formURL) }}" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $formTitle }}</h4>
                    <h6 class="card-subtitle">Order Details</h6>
                    {{$errors}}
                    @csrf
                    <div class="form-group">
                        <label>User Or Guest?</label>
                        <div class="input-group mb-3">
                            <select name=guest id=guest class="form-control custom-select" style="width: 100%; height:36px;" onchange="toggleGuest()" required>
                                <option value=1 
                                @if(old('guest')==1)
                                selected
                                @endif
                                >Guest</option>
                                <option value=2
                                @if(old('guest')==2)
                                selected
                                @endif>Registered User</option>
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('guest')}}</small>
                    </div>

                    <div id=isuser style="display: none">
                        <input type="hidden" name=user id=userID>
                        <div class="form-group">
                            <label>User</label>
                            <div class="input-group mb-3">
                                <select name=userSel id=userSel class="select2 form-control custom-select" style="width: 100%; height:36px;" onchange="loadUser()">
                                    <option value="" disabled selected>Pick From Users</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}%%{{ $user->USER_AREA_ID}}%%{{$user->USER_ADRS}}" @if(old('user')==$user->id)
                                        selected
                                        @endif
                                        >
                                        {{$user->USER_NAME}} - {{$user->USER_MOBN}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="text-danger">{{$errors->first('user')}}</small>
                        </div>
                    </div>

                    <div id=isguest style="display: block">
                        <div class="form-group">
                            <label>Guest Name</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Full Name" name=guestName value="{{ old('guestName')}}">
                            </div>
                            <small class="text-danger">{{$errors->first('guestName')}}</small>
                        </div>

                        <div class="form-group">
                            <label>Guest Mobile Number</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Guest Mobile Number" name=guestMob value="{{ old('guestMob')}}">
                            </div>
                            <small class="text-danger">{{$errors->first('guestMob')}}</small>
                        </div>
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
                            <textarea class="form-control" name="note"  rows="3">{{old('note')}}</textarea>
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
                            <div class="col-lg-9">
                                <div class="input-group mb-2">
                                    <select name=item[] class="form-control select2  custom-select" id=inventory1 onchange="changeMax(inventory1)" required>
                                        <option disabled hidden selected value="">Model</option>
                                        @foreach($inventory as $item)
                                        <option value="{{ $item->id }}">
                                            {{$item->product->PROD_NAME}} - {{$item->color->COLR_NAME}} - {{$item->size->SIZE_NAME}} - Available:{{$item->INVT_CUNT}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
    

                            <div class="col-lg-3">
                                <div class="input-group mb-3">
                                    <input type="number" step=1 id=count1 class="form-control amount" placeholder="Items Count" min=0 name=count[] aria-describedby="basic-addon11" required>
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
   concatString +=   '<div class="col-lg-9">\
                                <div class="input-group mb-2">\
                                    <select name=item[] class="form-control select2  custom-select" id=inventory' + room + ' onchange="changeMax(inventory' + room + ')" required>\
                                        <option disabled hidden selected value="">Model</option>\
                                        @foreach($inventory as $item)\
                                        <option value="{{ $item->id }}">\
                                            {{$item->product->PROD_NAME}} - {{$item->color->COLR_NAME}} - {{$item->size->SIZE_NAME}} - Available:{{$item->INVT_CUNT}}</option>\
                                        @endforeach\
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
   
   function changeMax(callerID) {
       itemIndex = callerID.id.substring(9, callerID.id.length)
        count = document.getElementById('count' + itemIndex) 
        optionString = callerID.options[callerID.selectedIndex].innerHTML
        count.max = optionString.substring(optionString.indexOf(":", optionString.length-10)+1 ,optionString.length)
   }


    function toggleGuest(){
    var selectaya = document.getElementById("guest");
        var userDiv = document.getElementById("isuser");
        var guestDiv = document.getElementById("isguest");
        if(selectaya.value == "1") // Guest
        {
            userDiv.style.display = "none";
            guestDiv.style.display = "block";
            $('#areaSel').val(null); // Select the option with a value of '1'
            $('#areaSel').trigger('change');
        } else { // User
     
            userDiv.style.display = "block";
            guestDiv.style.display = "none";
            loadUser();
        }
}

        function loadUser(){
            var selectaya = document.getElementById("userSel");
            var areaSelect = document.getElementById("areaSel");
            userInfo = selectaya.value.split('%%') 
            userInput = document.getElementById("userID");
            userInput.value = userInfo[0]
            var opts = areaSelect.options;

            for (var opt, j = 0; opt = opts[j]; j++) {
                if (opt.value == userInfo[1]) {                 
                    $('#areaSel').val(userInfo[1]); // Select the option with a value of '1'
                    $('#areaSel').trigger('change');
                    break;
                }
            }  
            if(typeof userInfo[2] !== 'undefined'){
            var userAdrs = document.getElementById("userAdrs");
            userAdrs.innerHTML =  userInfo[2]       

            }

        }
</script>
@endsection