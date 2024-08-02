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
                            <select name=source class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                <option value="" disabled selected>Pick From Order Sources</option>
                                @foreach($sources as $source)
                                <option value="{{ $source->id }}" @if(old('source')==$source->id)
                                    selected
                                    @endif
                                    >
                                    {{$source->ORSC_NAME}} - Account: {{$source->client_account?->CLNT_NAME}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('user')}}</small>
                    </div>

                    @if ($isOnline)

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

                    @endif

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
                                    @elseif(!$isOnline && $option->id == 4)
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

                    @if($isOnline)
                    <div class="row ">

                        <div id="dynamicContainer" class="nopadding row col-lg-12">
                        </div>

                        <div class="row col-lg-12">
                            <div class="col-lg-3">
                                <div class="input-group mb-2">
                                    <select name=item[] class="form-control select2  custom-select" style="width:100%" required>
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
                                    <select name=size[] class="form-control select2  custom-select" style="width:100%" required>
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
                                    <input type="number" step=0.01 id=price class="form-control amount" placeholder="Item Price" min=0 name=price[] aria-describedby="basic-addon11" required>

                                </div>
                            </div>


                            <div class="col-lg-3">
                                <div class="input-group mb-3">
                                    <input type="number" step=1 id=count class="form-control amount" placeholder="Items Count" min=0 name=count[] aria-describedby="basic-addon11" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-success" id="dynamicAddButton" type="button" onclick="addOnlineToab();"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=row>
                        <div class="col-lg-6">
                            <h4 class="card-title">Number of Items</h4>
                            <h6 class="card-subtitle" id=itemsCount>0</h6>

                        </div>
                        <div class="col-lg-6">
                            <h4 class="card-title">Total Price</h4>
                            <h6 class="card-subtitle" id=totalPrice>0EGP</h6>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success mr-2">Submit</button>

                    <button type="button" onclick="calculateOnlinePrice()" class="btn btn-info mr-2">Calculate</button>


                    @else
                    <div class="row p-l-10 p-r-10 p-b-10">
                        <h5 class="card-title p-l-10">Items Added</h5>
                        <ul class="col-lg-12  list-group" id=itemsList>


                        </ul>
                    </div>
                    <div class="row p-l-10 p-r-10">
                        <h5 class="col-lg-12 card-title p-l-10">Sales Summary</h5>
                    </div>
                    <div class="row p-l-20 p-r-20">
                        <div class="col-lg-4">
                            <strong>Totals</strong>
                        </div>

                        <div class="col-lg-4">
                            <strong>Number of Items</strong>
                            <p id=numberOfInv>0</p>
                        </div>

                        <div class="col-lg-4 p-r-20">
                            <strong>Price</strong>
                            <p id=totalPrice>0</p>
                        </div>
                    </div>
                    <hr>

                    <label class="nopadding" for="input-file-now-custom-1"><strong>Entry Details</strong></label>
                    <div class="row ">



                        <div class="nopadding row col-lg-12">
                            <div class="col-lg-2">
                                <div class="input-group mb-2">
                                    <select name=finished[] id=finished[] class="form-control select2  custom-select" required>
                                        <option disabled hidden selected value="">Finished Inventory</option>
                                        @foreach($finished as $item)
                                        <option value="{{ $item->id }}">
                                            {{$item->BRND_NAME}} - {{$item->MODL_UNID}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-lg-1">
                                <input type="number" step=1 min=0 class="form-control amount" placeholder="36" name=amount36[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11">
                            </div>
                            <div class="col-lg-1">
                                <input type="number" step=1 min=0 class="form-control amount" placeholder="38" name=amount38[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11">
                            </div>
                            <div class="col-lg-1">
                                <input type="number" step=1 min=0 class="form-control amount" placeholder="40" name=amount40[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11">
                            </div>

                            <div class="col-lg-1">
                                <input type="number" step=1 min=0 class="form-control amount" placeholder="42" name=amount42[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11">
                            </div>
                            <div class="col-lg-1">

                                <input type="number" step=1 min=0 class="form-control amount" placeholder="44" name=amount44[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11">
                            </div>
                            <div class="col-lg-1">
                                <input type="number" step=1 min=0 class="form-control amount" placeholder="46" name=amount46[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11">
                            </div>
                            <div class="col-lg-1">
                                <input type="number" step=1 min=0 class="form-control amount" placeholder="48" name=amount48[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11">
                            </div>
                            <div class="col-lg-1">
                                <input type="number" step=1 min=0 class="form-control amount" placeholder="50" name=amount50[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11">
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group mb-3">
                                    <input type="number" step=0.01 class="form-control amount" placeholder="Price" name=price[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11"
                                        required>
                                    <div class="input-group-append">
                                        <button class="btn btn-success" id="dynamicAddButton" type="button" onclick="addOfflineToab();"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="dynamicContainer">
                        </div>

                    </div>


                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <button type="button" onclick="calculateOfflineTotals()" class="btn btn-info mr-2">Calculate</button>
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
   function addOnlineToab() {
   
   room++;
   var objTo = document.getElementById('dynamicContainer')
   var divtest = document.createElement("div");
   divtest.setAttribute("class", "nopadding row col-lg-12 removeclass" + room);
   var rdiv = 'removeclass' + room;
   var concatString = "";
   concatString +=   '<div class="col-lg-3">\
                                <div class="input-group mb-2">\
                                    <select name=item[] class="form-control select2  custom-select" style="width:100%" required>\
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
   concatString += '   <div class="col-lg-3">\
                                        <div class="input-group mb-3">\
                                            <input type="number" step=0.01 id=price class="form-control amount" placeholder="Item Price" min=0 name=price[]\ aria-describedby="basic-addon11" required>\
                                        </div>\
                                    </div>';
   concatString +=                    " <div class='col-lg-3'>\
                               <div class='input-group mb-3'>\
                                   <input type='number' step=1 class='form-control amount' placeholder='Items Count' min=0 id=count" + room + "\
                                       name=count[] \
                                       aria-describedby='basic-addon11' required>\
                                   <div class='input-group-append'>\
                                    <button class='btn btn-danger' type='button' onclick='removeOnlineToab(" + room + ");'><i class='fa fa-minus'></i></button>\
                                   </div>\
                               </div>\
                           </div>";
   
   divtest.innerHTML = concatString;
   
   objTo.appendChild(divtest);
   $(".select2").select2()

   }

   function removeOnlineToab(rid) {
    $('.removeclass' + rid).remove();

    }

    function calculateOnlinePrice(){
        counts = document.getElementsByName('count[]')
        prices = document.getElementsByName('price[]')
        count = 0;
        totalPrice = 0;
        i = 0;

        counts.forEach(element => {
          count += parseInt(element.value)  
          totalPrice += (parseInt(element.value) * parseFloat(prices[i++].value))
        });

        totalDiv = document.getElementById('itemsCount')
        totalDiv.innerHTML = count

        priceDiv = document.getElementById('totalPrice')
        priceDiv.innerHTML = totalPrice + "EGP"
        
    }

    function calculateOfflineTotals(){
    var numberOfInv = 0;
    var price = 0;
    var i = 0;
    
    amount36 = document.forms[1].elements['amount36[]'];
    amount38 = document.forms[1].elements['amount38[]'];
    amount40 = document.forms[1].elements['amount40[]'];
    amount42 = document.forms[1].elements['amount42[]'];
    amount44 = document.forms[1].elements['amount44[]'];
    amount46 = document.forms[1].elements['amount46[]'];
    amount48 = document.forms[1].elements['amount48[]'];
    amount50 = document.forms[1].elements['amount50[]'];
    prices   = document.forms[1].elements['price[]'];
    finished = document.forms[1].elements['finished[]'];

    document.getElementById('itemsList').innerHTML = "";

    if (typeof prices.length === "undefined" && finished.selectedIndex!=0) {
    console.log("1 row")
        numberOfInv =  Number(amount36.value) + Number(amount38.value)  + Number(amount40.value) + Number(amount42.value) + Number(amount44.value) + Number(amount46.value)  + Number(amount48.value)  + Number(amount50.value ) ;
        price = numberOfInv * prices.value;
        
        document.getElementById('itemsList').innerHTML = "<li class='list-group-item' >\
                        <div class='row'>\
                            <div class='col-lg-4'> " + finished.options[finished.selectedIndex].innerHTML + " </div>\
                            <div class='col-lg-4'> " + numberOfInv + " </div>\
                            <div class='col-lg-4 p-l-10'> " + price.toFixed(1).replace(/\d(?=(\d{3})+\.)/g, '$&,') + "</div>\
                        </div>\
                        </li>";
    } else for(i ; i < amount36.length ; i++){
        if(finished[i].selectedIndex!=0){

            let row = Number(amount36[i].value) + Number(amount38[i].value)  + Number(amount40[i].value) + Number(amount42[i].value) + Number(amount44[i].value) + Number(amount46[i].value)  + Number(amount48[i].value)  + Number(amount50[i].value ) ;
            numberOfInv += row;
            price += row * prices[i].value;
            document.getElementById('itemsList').innerHTML += " <li class='list-group-item' >\
                            <div class='row'>\
                                <div class='col-lg-4'> " + finished[i].options[finished[i].selectedIndex].innerHTML + " </div>\
                                <div class='col-lg-4'> " + row + " </div>\
                                <div class='col-lg-4 p-l-10'> " + (prices[i].value * row).toFixed(1).replace(/\d(?=(\d{3})+\.)/g, '$&,'); + "</div>\
                            </div>\
                            </li>";
        }
    }

    document.getElementById('numberOfInv').innerHTML = numberOfInv;
    document.getElementById('totalPrice').innerHTML = price.toFixed(1).replace(/\d(?=(\d{3})+\.)/g, '$&,');
 }

 function addOfflineToab() {

        room++;
        var objTo = document.getElementById('dynamicContainer')
        var divtest = document.createElement("div");
        divtest.setAttribute("class", " row col-lg-12 removeclass" + room);
        var rdiv = 'removeclass' + room;
        var concatString = "";
        concatString +=   " <div class='col-lg-2'>\
                                            <div class='input-group mb-2'>\
                                                <select name=finished[] class='select2 form-control custom-select' style='width: 100%; height:50px;' required>\
                                                    <option disabled selected hidden value='' >Finished Inventory</option>\
                                                    @foreach($finished as $item)\
                                                    <option value='{{ $item->id }}'>\
                                                    {{$item->BRND_NAME}} - {{$item->MODL_UNID}} </option>\
                                                    @endforeach \
                                                </select>\
                                            </div>\
                                        </div>\
                                    <div class='col-lg-1'>\
                                        <input type='number'  step=1  min=0 class='form-control amount' placeholder='36' name=amount36[] aria-label='Total Amount in Meters' aria-describedby='basic-addon11'  >\
                                    </div>\
                                    <div class='col-lg-1'>\
                                        <input type='number'  step=1  min=0 class='form-control amount' placeholder='38' name=amount38[] aria-label='Total Amount in Meters' aria-describedby='basic-addon11'  >\
                                        </div> ";
                concatString +=    "<div class='col-lg-1'>\
                                        <input type='number'  step=1  min=0 class='form-control amount' placeholder='40' name=amount40[] aria-label='Total Amount in Meters' aria-describedby='basic-addon11'  >\
                                </div>\
                                <div class='col-lg-1'>\
                                        <input type='number'  step=1  min=0 class='form-control amount' placeholder='42' name=amount42[] aria-label='Total Amount in Meters' aria-describedby='basic-addon11'  >\
                                    </div>\
                                    <div class='col-lg-1'>\
                                        <input type='number'  step=1  min=0 class='form-control amount' placeholder='44' name=amount44[] aria-label='Total Amount in Meters' aria-describedby='basic-addon11'  >\
                                    </div>";
                concatString +=      "<div class='col-lg-1'>\
                                        <input type='number'  step=1  min=0  class='form-control amount' placeholder='46' name=amount46[] aria-label='Total Amount in Meters' aria-describedby='basic-addon11'  >\
                                    </div>\
                                    <div class='col-lg-1'>\
                                        <input type='number'  step=1  min=0 class='form-control amount' placeholder='48' name=amount48[] aria-label='Total Amount in Meters' aria-describedby='basic-addon11'  >\
                                    </div>\
                                    <div class='col-lg-1'>\
                                    <input type='number'  step=1  min=0  class='form-control amount' placeholder='50' name=amount50[]    >\
                                    </div>\
                                    <div class='col-lg-2'>\
                                    <div class='input-group mb-3'>\
                                        <input type='number' step=0.01 class='form-control amount' placeholder='Price' name=price[] aria-label='Each Item Price' aria-describedby='basic-addon11'  required>\
                                        <div class='input-group-append'>\
                                            <button class='btn btn-danger' type='button' onclick='removeOfflineToab(" + room + ");'><i class='fa fa-minus'></i></button>\
                                        </div>\
                                    </div>";

        divtest.innerHTML = concatString;

        objTo.appendChild(divtest);
        $(".select2").select2();

}

function removeOfflineToab(rid) {
        $('.removeclass' + rid).remove();
    }


    var bar = ''
 var selecteds = [];
    document.onkeyup = function (evt) {
    
        try {
        if (evt.keyCode == 13)// Enter key pressed
        {
        selectat = document.forms[1].elements['finished[]'];
        console.log(bar);

        lastSelect = selectat[selectat.length-1]
        var $selectat = $('select[name ="finished[]"]') 
        var $lastSelect = $selectat.eq($selectat.length-1)
        let noOfRows = $selectat.length-1
        console.log(bar)
        entries = bar.split(' ');    
        let selectedID = selectByText($lastSelect, entries[0])
        if(selectedID){ //found
        var isFound = isFoundBefore(selectedID);
        console.log(isFound)
        if(isFound === false){
            row = noOfRows
            $lastSelect.val(selectedID)
            $lastSelect.trigger('change')
            addSize(row, entries[1], true)
            addToab() 
        } else if (Number.isInteger(isFound)){
            addSize(isFound, entries[1], false)
        }
        calculateOfflineTotals()

        }
        bar=''
        } else if (evt.keyCode!=16){
            bar += evt.key;
        };
        } catch(e){
            bar = ''
        }
    }
 
        function selectByText ($el, term) {
            doma = $el.get(0)
            opts = doma.options
            let ret;
            let found=false;
            let i = 0;
            opts = Array.from(opts);
            for(i=0 ; i < opts.length ; i++) {
                if(opts[i].innerHTML.toLowerCase().includes(term.toLowerCase())){
                    console.log(opts[i].innerHTML)
                    return opts[i].value
                }
            }

            return  false;
        } 

        function isFoundBefore(val){
            selectatGodad = document.getElementsByName('finished[]');
            for(i=0 ; i < selectatGodad.length ; i++) {
                if(selectatGodad[i].options[selectatGodad[i].selectedIndex].value == val){
                    return i
                }
            } 
            return false;
        } 

        function addSize(row, size){
            amount = document.getElementsByName('amount' + size + '[]');
            console.log('amount' + size + '[] : ' + row)
            if(isNaN(amount[row].value) || amount[row].value == ''){
                amount[row].value = 1 ;
            } else {
                amount[row].value = parseInt(amount[row].value) + 1
            }
            
        }
   
</script>
@endsection