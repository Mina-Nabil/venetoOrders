@extends('layouts.app')


@section('content')


<div class="row">
    <!-- Column -->
    <div class="col-lg-12 col-xlg-3 col-md-5">
        <div class="card">
            @switch($order->ORDR_STTS_ID)
            @case(1)
            <div class="card-header bg-info text-light">New Order</div>
            @break
            @case(2)
            <div class="card-header bg-warning text-light">Order is Ready!</div>
            @break
            @case(3)
            <div class="card-header bg-dark text-light">Order in Delivery!</div>
            @break
            @case(4)
            <div class="card-header bg-success text-light">Order Delivered</div>
            @break
            @case(5)
            <div class="card-header bg-danger text-light">Order Cancelled :(</div>
            @break
            @case(6)
            <div class="card-header bg-primary text-light">Return Order :(</div>
            @break
            @default
            <div class="card-header bg-dark text-light">Order Details</div>
            @endswitch

            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="font-bold">
                            Ordered On
                        </div>
                        <p>{{$order->ORDR_OPEN_DATE}}</p>
                    </div>
                    <div class="col-md-2">
                        <div class="font-bold">
                            Area
                        </div>
                        <p>{{$order->AREA_NAME}}</p>
                    </div>
                    <div class="col-md-2">
                        <div class="font-bold">
                            Client Name
                        </div>
                        <p> {{$order->ORDR_GEST_NAME}}
                            {{-- @if(!$order->ORDR_GEST_NAME)
                            <a href="{{url('users/profile/' . $order->ORDR_USER_ID )}}">
                            @endif
                            {{($order->ORDR_GEST_NAME) ? $order->ORDR_GEST_NAME . " (Guest)": $order->USER_NAME . " (User)"}}
                            @if(!$order->ORDR_GEST_NAME)
                            </a>
                            @endif --}}
                        </p>
                    </div>
                    <div class="col-md-2">
                        <div class="font-bold">
                            Client Phone
                        </div>
                        <p>{{($order->ORDR_GEST_MOBN) ? $order->ORDR_GEST_MOBN : $order->USER_MOBN}}</p>
                    </div>
                    <div class="col-md-2">
                        <div class="font-bold">
                            Payment Option
                        </div>
                        <p>{{$order->PYOP_NAME}}</p>
                    </div>
                    <div class="col-md-2">
                        <div class="font-bold">
                            Total {{($order->ORDR_DISC > 0) ? "(Discount)" : ""}}
                        </div>
                        <p>{{$order->ORDR_TOTL ." EGP"}} {{($order->ORDR_DISC > 0) ? "(" .$order->ORDR_DISC. "EGP)" : ""}}</p>
                    </div>
                    <div class="col-md-4">
                        <div class="font-bold">
                            Delivery Address
                        </div>
                        <p>{{$order->ORDR_ADRS}}</p>
                    </div>
                    <div class="col-md-4">
                        <div class="font-bold">
                            Note
                        </div>
                        <p>{{$order->ORDR_NOTE}}</p>
                    </div>
                    <div class="col-md-4">
                        <div class="font-bold">
                            Source
                        </div>
                        <p>{{$order->ORSC_NAME}}: {{$order->CLNT_SRNO}}-{{$order->CLNT_NAME}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-12 col-xlg-9 col-md-7">
        <div class="card">
            <!-- Nav tabs -->
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item"> <a class="nav-link active" role="tab" data-toggle="tab" href="#status">Order Status</a> </li>
                    <li class="nav-item"> <a class="nav-link" role="tab" data-toggle="tab" href="#details">Order Details</a> </li>
                    <li class="nav-item"> <a class="nav-link" role="tab" data-toggle="tab" href="#additems">Add Items</a> </li>
                    <li class="nav-item"> <a class="nav-link" role="tab" data-toggle="tab" href="#driver">Assign Driver</a> </li>
                    <li class="nav-item"> <a class="nav-link" role="tab" data-toggle="tab" href="#payment ">Payments</a> </li>
                    <li class="nav-item"> <a class="nav-link" role="tab" data-toggle="tab" href="#settings">Edit Order Info</a> </li>
                    <li class="nav-item"> <a class="nav-link" role="tab" data-toggle="tab" href="#timeline">Timeline</a> </li>
                </ul>
            </div>
            <!-- Tab panes -->
            <div class="tab-content">
                <!--Status tab-->
                <div class="tab-pane active" id="status" role="tabpanel">
                    <div class="card-body">
                        <h4 class="card-title">Order Status</h4>
                        <h6 class="card-subtitle">Showing Order Status Summary before proceeding to delivery</h6>
                        <ul>
                            @if(isset($order->ORDR_DASH_ID) && is_numeric($order->ORDR_DASH_ID))
                            <li>
                                <p class="text-muted">Order opened by {{$order->DASH_USNM}} </p>
                            </li>
                            @else
                            <li>
                                <p class="text-muted">Order opened by client directly </p>
                            </li>
                            @endif
                            @if($isPartiallyReturned)
                            <li>
                                <p class="text-muted"><strong>Order Partially Returned</strong> items can be returned from 'Order Details' tab</p>
                            </li>
                            @endif
                            <li id=readyStatement>
                                @if($isFullyReturned || $isCancelled)
                                <p class="text-muted"><strong>Returned or Cancelled order</strong>, check returned items id 'Order Details'
                                </p>
                                @elseif($isOrderReady)
                                <p class="text-muted">All Items Ready, you can set the Order as "Ready"
                                    <i class="fas fa-check-circle" style="color:lightgreen"></i>
                                </p>
                                @else
                                <p class="text-muted">Please confirm all items in order list before setting Order as "Ready"
                                    <i class=" fas fa-exclamation-triangle" style="color:#fec107"></i>
                                </p>
                            </li>
                            @endif
                            @isset($order->ORDR_DRVR_ID)
                            <li>
                                <p class="text-muted">{{$order->DRVR_NAME}} is currently assigned to this order
                                    <i class="fas fa-check-circle" style="color:lightgreen"></i>
                                </p>
                            </li>
                            @elseif(!$isFullyReturned && !$isCancelled)
                            <li>
                                <p class="text-muted">Please assign a driver before changing status to (In Delivery)
                                    <i class=" fas fa-exclamation-triangle" style="color:#fec107"></i>
                                </p>
                            </li>
                            @endisset
                            <li>
                                @if($remainingMoney != 0)
                                <p class="text-muted">Payment not yet fully collected please collect payment before setting order as Delivered, {{$remainingMoney}}EGP remaining
                                    <i class=" fas fa-exclamation-triangle" style="color:#fec107"></i>
                                </p>
                                @elseif($isFullyReturned)
                                <p class="text-muted">Total returned items cost {{$order->ORDR_TOTL}}
                                </p>
                                @else
                                <p class="text-muted">Payment fully collected
                                    <i class="fas fa-check-circle" style="color:lightgreen"></i>
                                </p>
                                @endif
                            </li>
                        </ul>
                        @switch($order->ORDR_STTS_ID)
                        @case(1)
                        <button class="btn btn-warning mr-2" id=readyButton onclick="confirmAndGoTo('{{url($setOrderReadyUrl)}}', 'Set Order as Ready')" {{($isOrderReady) ? '' : "disabled"}}>
                            Order Is Ready For Shipment
                        </button>
                        <button class="btn btn-danger mr-2" onclick="confirmAndGoTo('{{url($setOrderCancelledUrl)}}', 'Cancel the Order')">Cancel Order</button>
                        @break
                        @case(2)
                        <button class="btn btn-info mr-2" onclick="confirmAndGoTo('{{url($setOrderNewUrl)}}', 'Set Order as New')">Set Order as New</button>
                        <button class="btn btn-dark mr-2" id=inDeliveryButton onclick="confirmAndGoTo('{{url($setOrderInDeliveryUrl)}}', 'Set Order as In Delivery')"
                            {{(isset($order->ORDR_DRVR_ID)) ? '' : "disabled"}}>Ship Order For Delivery</button>
                        <button class="btn btn-danger mr-2" onclick="confirmAndGoTo('{{url($setOrderCancelledUrl)}}', 'Cancel the Order')">Cancel Order</button>
                        @break
                        @case(3)
                        @if(!$isPartiallyReturned)
                        <button class="btn btn-info mr-2" onclick="confirmAndGoTo('{{url($linkNewReturnUrl)}}', 'Link new Return Order')">Set As New</button>
                        <button class="btn btn-primary mr-2" onclick="confirmAndGoTo('{{url($linkNewReturnUrl)}}', 'Link new Return Order')">Link New Return</button>
                        @else
                        <button class="btn btn-info mr-2" onclick="confirmAndGoTo('{{url('orders/details/' . $order->ORDR_RTRN_ID)}}', 'Go to the Return Order')">
                            Check Return Order</button>
                        @endif
                        <button class="btn btn-success mr-2" onclick="confirmAndGoTo('{{url($setOrderDeliveredUrl)}}', 'Set Order as Delivered')" @if($remainingMoney !=0) disabled @endif>Set Order as
                            Delivered</button>
                        <button class="btn btn-danger mr-2" onclick="confirmAndGoTo('{{url($setOrderCancelledUrl)}}', 'Cancel the Order')">Cancel Order</button>
                        @break
                        @case(4)
                        <button class="btn btn-danger mr-2" onclick="confirmAndGoTo('{{url($returnUrl)}}', 'Return the Order')">Return Order</button>
                        @break
                        @default
                        @endswitch
                    </div>
                </div>



                {{-- Hidden Values carrying each item state --}}
                @foreach($items as $item)
                <input type="hidden" id="isReady{{$item->id}}" value={{$item->ORIT_VRFD}}>
                @endforeach

                {{-- Order Details --}}
                <div class="tab-pane" id="details" role="tabpanel">
                    <div class="card-body">
                        <div class="table-responsive m-t-5">
                            <table id="itemsTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]">
                                <thead>
                                    <th>Ready?</th>
                                    <th>Model</th>
                                    <th>Size</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                    @if($order->ORDR_STTS_ID==1 || $isPartiallyReturned)
                                    <th>Action</th>
                                    @endif
                                </thead>
                                <tbody>
                                    @foreach($items as $item)

                                    <tr id="item{{$item->id}}">
                                        <td id="ready{{$item->id}}">
                                            @if($item->ORIT_VRFD)
                                            <i class="fas fa-check-circle" style="color:lightgreen">
                                                @else
                                                <i class=" fas fa-exclamation-triangle" style="color:#fec107">
                                                    @endif
                                        </td>
                                        <td>{{$item->BRND_NAME}}-{{$item->MODL_UNID}}</td>
                                        <td>{{$item->ORIT_SIZE}}</td>
                                        <td>{{$item->ORIT_CUNT}}</td>
                                        <td>{{$item->ORIT_PRCE}}</td>
                                        <td>{{$item->ORIT_CUNT * $item->ORIT_PRCE}}</td>
                                        @if($order->ORDR_STTS_ID==1 || $isPartiallyReturned)
                                        <td>
                                            <div class="btn-group">
                                                <button style="padding:.1rem .2rem" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    Action
                                                </button>
                                                @if($isPartiallyReturned)
                                                <div class="dropdown-menu">
                                                    <button class="dropdown-item" data-toggle="modal" data-target="#changeQuantity{{$item->id}}">Return Item</button>
                                                </div>
                                                @else
                                                <div class="dropdown-menu">
                                                    @if(!$item->ORIT_VRFD)
                                                    @if($isInventory)
                                                    <button class="dropdown-item" onclick="toggleReady({{$item->id}}, this)">Set as Ready!</button>
                                                    @endif
                                                    @else
                                                    <button class="dropdown-item" onclick="toggleReady({{$item->id}}, this)">Remove Ready Flag!</button>
                                                    @endif
                                                    <button class="dropdown-item" data-toggle="modal" data-target="#changeQuantity{{$item->id}}">Change Quantity</button>
                                                    <button class="dropdown-item" onclick="deleteItem({{$item->id}})">Remove Item</button>
                                                </div>
                                                @endif
                                            </div>
                                        </td>
                                        @endif
                                    </tr>
                                    @if($order->ORDR_STTS_ID==1 || $isPartiallyReturned)
                                    <div id="changeQuantity{{$item->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Change Model Quantity</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                </div>
                                                <form action="{{ url('orders/change/quantity') }}" method=post>
                                                    @csrf
                                                    <div class="modal-body">
                                                        <input type=hidden name=itemID value="{{$item->id}}">
                                                        @if(!$isPartiallyReturned)
                                                        <div class="form-group col-md-12 m-t-0">
                                                            <h5>Price</h5>
                                                            <input type="number" step=0.01 class="form-control form-control-line" name=price value="{{$item->ORIT_PRCE}}" required>
                                                        </div>
                                                        @endif
                                                        <div class="form-group col-md-12 m-t-0">
                                                            <h5>Amount</h5>
                                                            <input type="number" step=1 class="form-control form-control-line" name=count value="{{$item->ORIT_CUNT}}" @if($isPartiallyReturned)
                                                                max={{$item->ORIT_CUNT}} @endif required>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-warning waves-effect waves-light">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!--Add Item tab-->
                <div class="tab-pane" id="additems" role="tabpanel">
                    <div class="card-body">
                        <h4 class="card-title">Add More Items</h4>
                        <h6 class="card-subtitle">Pick from our inventory</h6>
                        @if($order->ORDR_STTS_ID==1)
                        <form class="form pt-3" method="post" action="{{ url($addFormURL) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row ">
                                <div id="dynamicContainer" class="nopadding row col-lg-12">
                                </div>

                                <div class="row col-lg-12 nopadding">
                                    <div class="col-lg-3">
                                        <div class="input-group mb-2">
                                            <select name=item[] class="form-control select2 custom-select" style="width:100%" required>
                                                <option disabled hidden selected value="">Pick a Model</option>
                                                @foreach($finished as $item)
                                            <option value="{{ $item->id }}">{{$item->BRND_NAME}} - {{$item->MODL_UNID}}, Price: {{$item->FNSH_PRCE}}EGP</option>
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
                                            <input type="number" step=0.01 id=count class="form-control amount" placeholder="Item Price" min=0 name=price[] aria-describedby="basic-addon11" required>
                                         
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="input-group mb-3">
                                            <input type="number" step=1 id=count class="form-control amount" placeholder="Items Count" min=0 name=count[] aria-describedby="basic-addon11" required>
                                            <div class="input-group-append">
                                                <button class="btn btn-success" id="dynamicAddButton" type="button" onclick="addToab();"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success mr-2">Submit</button>
                        </form>
                        @else
                        <p class="text-muted">Order Can't be modified, only New Orders can be modified</p>
                        @endif

                    </div>
                </div>

                <!--Assign Driver tab-->
                <div class="tab-pane" id="driver" role="tabpanel">
                    <div class="card-body">
                        <h4 class="card-title">Assign Driver</h4>
                        <h6 class="card-subtitle">Assign a Driver to deliver the order</h6>
                        @if($order->ORDR_STTS_ID < 3) <!-- New and Ready orders -->
                            @isset($order->ORDR_DRVR_ID)
                            <p class="text-muted">{{$order->DRVR_NAME}} is currently assigned to this order</p>
                            @endisset
                            <form class="form pt-3" method="post" action="{{ url($assignDriverFormURL) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row ">
                                    <input type="hidden" name=id value="{{$order->id}}">
                                    <div class="row col-lg-12 nopadding">
                                        <div class="col-lg-9">
                                            <div class="input-group mb-2">
                                                <select name=driver class="form-control select2 custom-select" style="width: 100%" required>
                                                    <option disabled hidden selected value="">Pick a Driver</option>
                                                    @foreach($drivers as $driver)
                                                    <option value="{{ $driver->id }}" @if($order->ORDR_DRVR_ID == $driver->id)
                                                        selected
                                                        @endif
                                                        >{{$driver->DRVR_NAME}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>



                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success mr-2">Submit</button>
                            </form>
                            @elseif($order->ORDR_STTS_ID < 5)<!-- In delivery or Delivered Orders -->
                                <p class="text-muted">Order already shipped by {{$order->DRVR_NAME}}</p>
                                @else
                                <p class="text-muted">Order Cancelled :(</p>
                                @endif

                    </div>
                </div>


                <!--Add Item tab-->
                <div class="tab-pane" id="payment" role="tabpanel">
                    <div class="card-body">
                        <h4 class="card-title">Order Payments</h4>
                        <h6 class="card-subtitle">Total: {{$order->ORDR_TOTL}} - Paid: {{$order->ORDR_PAID}} - Discount: {{$order->ORDR_DISC}} - Remaining: {{$remainingMoney}} - Delivery
                            {{$order->ORDR_DLFE}} </h6>
                        @if($order->ORDR_STTS_ID < 4 ) <form class="form pt-3" method="post" action="{{ url($paymentURL) }}" enctype="multipart/form-data">
                            <input type="hidden" name=id value={{$order->id}}>
                            @csrf
                            <div class="form-group">
                                <label>Payment</label>
                                <div class="input-group mb-3">
                                    <input type="number" step=.01 class="form-control amount" placeholder="Items Count" min=0 max={{$remainingMoney}} name=payment value="0" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success mr-2">Collect Normal Payment</button>
                            </form>
                            <hr>
                            <form class="form pt-3" method="post" action="{{ url($discountURL) }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name=id value={{$order->id}}>
                                <div class="form-group">
                                    <label>Discount</label>
                                    <div class="input-group mb-3">
                                        <input type="number" step=.01 class="form-control amount" placeholder="Items Count" min=0 max={{$remainingMoney}} name=discount value="{{$order->ORDR_DISC}}"
                                            required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success mr-2">Set Discount</button>
                            </form>

                            @else
                            <p class="text-muted">Old Orders Payment & Discounts Can't be modified, only Delivery Payment can be modified</p>
                            @endif
                            <hr>
                            <form class="form pt-3" method="post" action="{{ url($deliveryPaymentURL) }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name=id value={{$order->id}}>
                                <div class="form-group">
                                    <label>Delivery Paid</label>
                                    <small class="text-italic">Delivery Area Rate: {{$order->AREA_RATE}}</small>
                                    <div class="input-group mb-3">
                                        <input type="number" step=.01 class="form-control amount" placeholder="Items Count" min=0 name=deliveryPaid value="{{$order->ORDR_DLFE}}" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success mr-2">Collect Delivery Payment</button>
                            </form>
                    </div>
                </div>

                <!--Settings tab-->
                <div class="tab-pane " id="settings" role="tabpanel">
                    <div class="card-body">
                        <div class="card-body">
                            <h4 class="card-title">Order Info</h4>
                            <h6 class="card-subtitle">Edit Order Info, Notes and Address</h6>
                            @if($order->ORDR_STTS_ID < 4 ) <form class="form pt-3" method="post" action="{{ url($paymentURL) }}" enctype="multipart/form-data">
                                <div class="form-group">

                                    <label>Area</label>
                                    <div class="input-group mb-3">
                                        <select name=area id=areaSel class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                            <option value="" disabled selected>Pick From Areas</option>
                                            @foreach($areas as $area)
                                            <option value="{{ $area->id }}" @if($order->ORDR_AREA_ID==$area->id)
                                                selected
                                                @endif
                                                >{{$area->AREA_NAME}} : {{$area->AREA_ARBC_NAME}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <small class="text-danger">{{$errors->first('area')}}</small>
                                </div>

                                <div class="form-group">
                                    <label>Delivery Address</label>
                                    <div class="input-group mb-3">
                                        <textarea class="form-control" name="address" id=userAdrs rows="3" required>{{$order->ORDR_ADRS}}</textarea>
                                    </div>
                                    <small class="text-danger">{{$errors->first('address')}}</small>
                                </div>
                                <div class="form-group">
                                    <label>Additional Notes</label>
                                    <div class="input-group mb-3">
                                        <textarea class="form-control" name="note" rows="3">{{$order->ORDR_NOTE}}</textarea>
                                    </div>
                                    <small class="text-danger">{{$errors->first('note')}}</small>
                                </div>
                                <button type="submit" class="btn btn-success mr-2">Submit</button>
                                </form>
                                @else
                                <p class="text-muted">Old Order Info can't be modified</p>
                                @endif
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="timeline" role="tabpanel">
                    <div class="card-body">
                        <h4 class="card-title">Order history</h4>
                        <h6 class="card-subtitle">Check all order changes & events</h6>
                        <ul class="list-group">
                            @foreach($timeline as $event)
                            <a href="javascript:void(0)" class="list-group-item list-group-item-action flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1 text-dark">{{$event->DASH_USNM}}</h5>
                                    <small>{{$event->created_at}}</small>
                                </div>
                                <p class="mb-1">{{$event->TMLN_TEXT}}</p>
                            </a>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
</div>
<script>
    function IsNumeric(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
  }
        function toggleReady(itemId, caller){
            isReady = document.getElementById("isReady"+itemId);
            action = '';
            if(isReady.value == "1"){
                action = 'Set Item as not Ready?'
            } else {
                action = 'Set Item as Ready?'
            }

            Swal.fire({
                text: "Are you sure you want to " + action + "?",
                icon: "warning",
                showCancelButton: true,
            }).then((isConfirm) => {
    
                if(isConfirm.value){
                   var http = new XMLHttpRequest();

                http.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                   
                    if (IsNumeric(this.responseText) ) {
                    cell = document.getElementById("ready"+itemId)
                    if(isReady.value == "1"){
                        cell.innerHTML = '<i class=" fas fa-exclamation-triangle" style="color:#fec107">'
                        isReady.value   = "0";
                        caller.innerHTML = "Set as Ready!"
                    } else {
                        cell.innerHTML = ' <i class="fas fa-check-circle" style="color:lightgreen">'
                        isReady.value   = "1";
                        caller.innerHTML = "Remove Ready Flag!"
                        
                    }
                    checkIfAllReady();
                    Swal.fire({
                        text: "Success",
                        icon: "success",
                    });
                    } else if (!IsNumeric(this.responseText)) {
                        Swal.fire({
                        text: "ERROR - Please refresh and try again",
                        icon: "error",
                    });
                    }
                }
                };
                http.open('GET', '{{url("orders/toggle/item")}}'+ '/'+ itemId)
                http.send();
            }
        });
        }

        function deleteItem(id){
            Swal.fire({
                text: "Are you sure you want to Delete this item from the order list?",
                icon: "warning",
                showCancelButton: true,
            }).then((isConfirm) => {
                if(isConfirm.value){
                window.location.href = '{{url("orders/delete/item")}}'+ '/'+ id;
                }
            });
        }

        function checkIfAllReady(){
            isReady = true;
            isReadyCells = document.querySelectorAll('[id^="isReady"]');
            isReadyCells.forEach(element => {
                if(element.value == '0')
                    isReady=false;
            });
            if(isReady){
                document.getElementById('readyButton').disabled = false
                document.getElementById('readyStatement').innerHTML = '<p class="text-muted">All Items Ready, you can set the Order as "Ready"\
                                    <i class="fas fa-check-circle" style="color:lightgreen"></i>\
                                </p>';
            } else {
                document.getElementById('readyButton').disabled = true
                document.getElementById('readyStatement').innerHTML = '<p class="text-muted">Please confirm all items in order list before setting Order as "Ready"\
                                    <i class=" fas fa-exclamation-triangle" style="color:#fec107"></i>\
                                </p>';
            }
        }

        function confirmAndGoTo(url, action){
            Swal.fire({
                text: "Are you sure you want to " + action + "?",
                icon: "warning",
                showCancelButton: true,
            }).then((isConfirm) => {
        if(isConfirm.value){
            window.location.href = url;
            }
        });
    }

</script>

<script src="{{ asset('assets/node_modules/jquery/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('assets/node_modules/datatables/datatables.min.js') }}"></script>
<script>
    $(function () {
            $(function () {

                var table = $('#itemsTable').DataTable({
                    "displayLength": 25,
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'excel',
                            title: 'Whale Dashboard',
                            footer: true,
                        }
                    ]
                });
            })
        })
</script>
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
   concatString +=   '<div class="col-lg-3">\
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
   concatString += '    <div class="col-lg-3">\
                                        <div class="input-group mb-3">\
                                            <input type="number" step=0.01 id=count class="form-control amount" placeholder="Item Price" min=0 name=price[]\ aria-describedby="basic-addon11" required>\
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