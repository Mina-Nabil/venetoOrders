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
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#history" role="tab">Order History</a> </li>
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#wishlist" role="tab">Wishlist</a> </li>
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#bought" role="tab">Items Bought</a> </li>
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#settings" role="tab">Settings</a> </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <!--second tab-->
           
            {{-- <div class="tab-pane active" id="history" role="tabpanel">
                <div class="card-body">
                    <h4 class="card-title">Add New Model Image</h4>
                    <form class="form pt-3" method="post" action="{{ url($imageFormURL) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label>Related Color</label>
                            <div class="input-group mb-3">
                                <select name=color class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                    <option value="" disabled selected>Pick From Colors</option>
                                    @foreach($colors as $color)
                                    <option value="{{ $color->id }}">{{$color->COLR_NAME}} - {{$color->COLR_ARBC_NAME}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="text-danger">{{$errors->first('name')}}</small>
                        </div>

                        <div class="form-group">
                            <label>New Image</label>
                            <div class="input-group mb-3">
                                <button type=button id="upload_widget" class="cloudinary-button">Upload files</button>
                                <input type=hidden id=uploaded name=uploadedImage value="{{old('uploadedImage')}}" />
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success mr-2">Submit</button>
                        @if($isCancel)
                        <a href="{{url($homeURL) }}" class="btn btn-dark">Cancel</a>
                        @endif
                    </form>
                </div>

                <hr>
                <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <?php $i=0; ?>
                        @foreach($user->images as $image)
                        <li data-target="#carouselExampleIndicators2" data-slide-to="{{$i}}" {{($i==0) ? 'class="active"' : ''}}></li>
                        <?php $i++; ?>
                        @endforeach
                    </ol>
                    <div class="carousel-inner" role="listbox">
                        <?php $i=0; ?>
                        @foreach($user->images as $image)
                        <div class="carousel-item {{($i==0) ? 'active' : ''}}">
                            <img class="img-fluid" src="{{$image->PIMG_URL}}" style="max-height:350; max-width:300; height:auto; width:auto;">
                        </div>
                        <?php $i++; ?>
                        @endforeach
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators2" role="button" data-slide="prev" style="background-color:#DCDCDC">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators2" role="button" data-slide="next" style="background-color:#DCDCDC">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
                <hr>
                <div>
                    <div class="table-responsive m-t-40">
                        <table class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]">
                            <thead>
                                <th>Url</th>
                                <th>Color</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @foreach ($user->images as $image)
                                <tr>
                                    <td><a target="_blank" href="{{$image->PIMG_URL}}">
                                            {{(strlen($image->PIMG_URL) < 25) ? $image->PIMG_URL : substr($image->PIMG_URL, 0, 25).'..' }}
                                        </a></td>
                                    <td>{{$image->color->COLR_NAME}}</td>
                                    <td>
                                        @if($image->id != $user->USER_PIMG_ID)
                                        <a href="javascript:void(0);">
                                            <div class="label label-info" onclick="confirmAndGoTo('{{url('products/setimage/'.$user->id.'/'.$image->id)}}', 'set this as the main Model Image')">
                                                Set As Main </div>
                                        </a>
                                        @else
                                        <a href="javascript:void(0);">
                                            <div class="label label-danger">Main Image</div>
                                        </a>
                                        @endif
                                    </td>
                                <tr>
                                    @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="wishlist" role="tabpanel">
                <div class="card-body">
                    <h4 class="card-title">Set Size Chart</h4>
                    <form class="form pt-3" method="post" action="{{ url($chartFormURL) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Size Chart</label>
                            <div class="input-group mb-3">
                                <select name=chart class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                    <option value="" disabled selected>Pick From Charts</option>
                                    @foreach($charts as $chart)
                                    <option value="{{ $chart->id }}" @isset($user->sizechart->id)
                                        {{($user->sizechart->id== $chart->id) ? 'selected' : ''}}
                                        @endisset
                                        >{{$chart->SZCT_NAME}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="text-danger">{{$errors->first('name')}}</small>
                        </div>
                        <button type="submit" class="btn btn-success mr-2">Submit</button>
                    </form>
                    <hr>
                    @isset($user->sizechart->id)
                    <img class="card-img" src="{{$user->sizechart->SZCT_URL}}" style="max-height:456; width:auto; height:auto;" alt="Card image">
                    <button type="button" onclick="confirmAndGoTo('{{url($removeChartURL)}}', 'unlink the Size Chart')" class="btn btn-danger mr-2">Unlink Size Chart</button>
                    @endisset
                </div>
            </div>

            <div class="tab-pane" id="bought" role="tabpanel">
                <div class="card-body">
                    <h4 class="card-title">Set Size Chart</h4>
                    <form class="form pt-3" method="post" action="{{ url($tagsFormURL) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Select Model Tags</label>
                            <div class="input-group mb-3">
                                <select class="select2 m-b-10 select2-multiple" style="width: 100%" multiple="multiple" data-placeholder="Choose From The Following Tags" name=tags[]>
                                    @foreach($tags as $tag)
                                    <option value="{{$tag->id}}" {{(in_array( $tag->id, $prodTagIDs)) ? 'selected' : ''}}>{{$tag->TAGS_NAME}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="text-danger">{{$errors->first('tags')}}</small>
                        </div>
                        <button type="submit" class="btn btn-success mr-2">Submit</button>
                    </form>
                </div>
            </div> --}}

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