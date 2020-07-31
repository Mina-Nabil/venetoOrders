@extends('layouts.app')


@section('content')

<div class="row">
    <!-- Column -->
    <div class="col-lg-4 col-xlg-3 col-md-5">
        <div class="card">
            <div class="card-body">
                <small class="text-muted">Client Name </small>
                <h6>{{$user->USER_NAME}}</h6>
                <small class="text-muted">Email address </small>
                <h6>{{$user->USER_MAIL}}</h6>
                <small class="text-muted p-t-30 db">Phone</small>
                <h6>{{$user->USER_MOBN}}</h6>
                <small class="text-muted p-t-30 db">Area</small>
                <h6>{{$user->area->AREA_NAME}}</h6>
                <small class="text-muted p-t-30 db">Address</small>
                <h6>{{$user->USER_ADRS}}</h6>

                <small class="text-muted p-t-30 db">Social Profile</small>
                <br />
                @isset($user->USER_FBTK)
                <button class="btn btn-circle btn-secondary"><i class="fab fa-facebook-f"></i></button>
                @else
                <button class="btn btn-circle btn-secondary"><i class="fas fa-at"></i></button>
                @endisset

            </div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-8 col-xlg-9 col-md-7">
        <div class="card">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs profile-tab" role="tablist">
                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#history" role="tab">Order History</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#cart" role="tab">Cart</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#wishlist" role="tab">Wishlist</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#bought" role="tab">Items Bought</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#settings" role="tab">Settings</a> </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!--Orders tab-->
                <div class="tab-pane active" id="history" role="tabpanel">
                    <div class="card-body">
                        <h4 class="card-title">User's Orders History</h4>
                        <h6 class="card-subtitle">Total Money Paid: {{$userMoney->paid}}, Discount offered: {{$userMoney->discount}}</h6>
                        <div class="col-12">
                            <x-datatable id="myTable" :title="$title ?? 'Orders History'" :subtitle="$subTitle ?? ''" :cols="$ordersCols" :items="$orderList" :atts="$orderAtts" :cardTitle="false" />
                        </div>
                    </div>
                </div>

                <!--Cart tab-->
                <div class="tab-pane" id="cart" role="tabpanel">
                    <div class="card-body">
                        <div class="col-12">
                            <x-datatable id="myTable2" :title="$title ?? 'Saved Cart'" :subtitle="$subTitle ?? ''" :cols="$cartCols" :items="$cartList" :atts="$cartAtts" :cardTitle="false" />
                        </div>
                    </div>
                </div>

                <!--Wishlist tab-->
                <div class="tab-pane" id="wishlist" role="tabpanel">
                    <div class="card-body">
                        <div class="col-12">
                            <x-datatable id="myTable3" :title="$title ?? 'Wishlist'" :subtitle="$subTitle ?? ''" :cols="$wishlistCols" :items="$wishlistList" :atts="$wishlistAtts"
                                :cardTitle="false" />
                        </div>
                    </div>
                </div>

                <!--Item Bought tab-->
                <div class="tab-pane" id="bought" role="tabpanel">
                    <div class="card-body">
                        <div class="col-12">
                            <x-datatable id="myTable4" :title="$title ?? 'Items Bought'" :subtitle="$subTitle ?? ''" :cols="$boughtCols" :items="$boughtList" :atts="$boughtAtts" :cardTitle="false" />
                        </div>
                    </div>
                </div>


                <div class="tab-pane" id="settings" role="tabpanel">
                    <div class="card-body">
                        <h4 class="card-title">Edit {{ $user->USER_NAME }}'s Info</h4>
                        <form class="form pt-3" method="post" action="{{ url($formURL) }}" enctype="multipart/form-data">
                            @csrf
                            <input type=hidden name=id value="{{(isset($user)) ? $user->id : ''}}">
                            <div class="form-group">
                                <label>Full Name*</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Full Name" name=name value="{{ (isset($user)) ? $user->USER_NAME : old('name')}}" required>
                                </div>
                                <small class="text-danger">{{$errors->first('name')}}</small>
                            </div>
                            <div class="form-group">
                                <label>Email*</label>
                                <div class="input-group mb-3">
                                    <input type="email" class="form-control" name=mail placeholder="User email" value="{{ (isset($user)) ? $user->USER_MAIL : old('mail')}}" required>
                                </div>
                                <div class="col-lg-12 bt-switch">
                                    <label>Verified?</label>
                                    <div class="input-group mb-3 ">
                                        <input type="checkbox" data-size="large" data-on-color="info" data-off-color="warning" @if(isset($user) && $user->USER_MAIL_VRFD==1)
                                        checked
                                        @endif
                                        data-on-text="Yes" data-off-text="No" name="isMailVerified">
                                    </div>
                                </div>
                                <small class="text-danger">{{$errors->first('mail')}}</small>
                            </div>

                            <div class="form-group">
                                <label>Password*</label>
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" placeholder="Password" name=pass {{(isset($user) ? "" : "required")}}>
                                </div>
                                <small class="text-danger">{{$errors->first('pass')}}</small>
                            </div>

                            <div class="form-group">
                                <label>Mobile Number*</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Mobile Number" name=mob value="{{ (isset($user)) ? $user->USER_MOBN : old('mob') }}" required>
                                </div>
                                <div class="col-lg-12 bt-switch">
                                    <label>Verified?</label>
                                    <div class="input-group mb-3 ">
                                        <input type="checkbox" data-size="large" data-on-color="info" data-off-color="warning" @if(isset($user) && $user->USER_MOBN_VRFD==1)
                                        checked
                                        @endif
                                        data-on-text="Yes" data-off-text="No" name="isMobVerified">
                                    </div>
                                </div>
                                <small class="text-danger">{{$errors->first('mob')}}</small>
                            </div>



                            <div class="form-group">
                                <label>Area*</label>
                                <div class="input-group mb-3">
                                    <select name=area class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                        <option value="" disabled selected>Select Area</option>
                                        @foreach($areas as $area)
                                        <option value="{{ $area->id }}" @if(isset($user) && $area->id == $user->USER_AREA_ID)
                                            selected
                                            @elseif($area->id == old('area'))
                                            selected
                                            @endif
                                            >{{$area->AREA_NAME}} - {{$area->AREA_ARBC_NAME}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="text-danger">{{$errors->first('area')}}</small>
                            </div>


                            <div class="form-group">
                                <label>Gender*</label>
                                <div class="input-group mb-3">
                                    <select name=gender class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                        <option value="" disabled selected>Pick A Gender</option>
                                        @foreach($genders as $gender)
                                        <option value="{{ $gender->id }}" @if(isset($user) && $gender->id == $user->USER_GNDR_ID)
                                            selected
                                            @elseif($gender->id == old('gender'))
                                            selected
                                            @endif
                                            >{{$gender->GNDR_NAME}} - {{$gender->GNDR_ARBC_NAME}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="text-danger">{{$errors->first('gender')}}</small>
                            </div>

                            <button type="submit" class="btn btn-success mr-2">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
</div>
@endsection

@section("js_content")
<script type="text/javascript" src="{{asset('assets/node_modules/multiselect/js/jquery.multi-select.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/node_modules/bootstrap-select/bootstrap-select.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>


<script src="https://widget.cloudinary.com/v2.0/global/all.js" type="text/javascript"></script>
<script>
    var myWidget = cloudinary.createUploadWidget({
    cloudName: 'sasawhale', 
    folder: "whale/models",
    uploadPreset: 'whalesec'}, (error, result) => { 
      if (!error && result && result.event === "success") { 
        document.getElementById('uploaded').value = result.info.url;
      }
    }
  )
  
  document.getElementById("upload_widget").addEventListener("click", function(){
      myWidget.open();
    }, false);

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
@endsection