@extends('layouts.app')

@section('head_content')

<link href="{{asset('assets/node_modules/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/node_modules/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" type="text/css" />

@endsection

@section('content')

<div class="row">
    <!-- Column -->
    <div class="col-lg-4 col-xlg-3 col-md-5">
        <div class="card"> <img class="card-img" src="{{$mainImage}}" style="max-height:420; max-width:420;width:auto; height:auto;" alt="Card image">
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-8 col-xlg-9 col-md-7">
        <div class="card">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs profile-tab" role="tablist">
                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#profile" role="tab">Model Info</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#stock" role="tab">Stock</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#sales" role="tab">Sales</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#images" role="tab">Images</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#chart" role="tab">Size Chart</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#tags" role="tab">Tags</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#settings" role="tab">Settings</a> </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!--second tab-->
                <div class="tab-pane active" id="profile" role="tabpanel">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Model Name</strong>
                                <br>
                                <p class="text-muted">{{$product->PROD_NAME}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Arabic Name</strong>
                                <br>
                                <p class="text-muted">{{$product->PROD_ARBC_NAME}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Available Sizes</strong>
                                <br>
                                <p class="text-muted">
                                    @foreach($product->sizes() as $size)
                                    {{$size->SIZE_NAME . ' '}}</p>
                                @endforeach
                            </div>
                            <div class="col-md-3 col-xs-6"> <strong>Location</strong>
                                <br>
                                <p class="text-muted">London</p>
                            </div>
                        </div>
                        <hr>
                        <strong>Description</strong>
                        <p class="m-t-30">{{$product->PROD_DESC}}</p>
                        <hr>
                        <strong>Arabic Description</strong>
                        <p class="m-t-30">{{$product->PROD_ARBC_DESC}}</p>
                    </div>
                </div>

                <div class="tab-pane" id="stock" role="tabpanel">
                    <div class="card-body">
                        <x-datatable id="myTable"  :title="$title" :subtitle="$subTitle" :cols="$cols" :items="$items" :atts="$atts" />
                    </div>
                </div>

                <div class="tab-pane" id="images" role="tabpanel">
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
                            @foreach($product->images as $image)
                            <li data-target="#carouselExampleIndicators2" data-slide-to="{{$i}}" {{($i==0) ? 'class="active"' : ''}}></li>
                            <?php $i++; ?>
                            @endforeach
                        </ol>
                        <div class="carousel-inner" role="listbox">
                            <?php $i=0; ?>
                            @foreach($product->images as $image)
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
                                    @foreach ($product->images as $image)
                                    <tr>
                                        <td><a target="_blank" href="{{$image->PIMG_URL}}">
                                                {{(strlen($image->PIMG_URL) < 25) ? $image->PIMG_URL : substr($image->PIMG_URL, 0, 25).'..' }}
                                            </a></td>
                                        <td>{{$image->color->COLR_NAME}}</td>
                                        <td>
                                            @if($image->id != $product->PROD_PIMG_ID)
                                            <a href="javascript:void(0);">
                                                <div class="label label-info" onclick="confirmAndGoTo('{{url('products/setimage/'.$product->id.'/'.$image->id)}}', 'set this as the main Model Image')">
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
                <div class="tab-pane" id="chart" role="tabpanel">
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
                                        <option value="{{ $chart->id }}" @isset($product->sizechart->id)
                                            {{($product->sizechart->id== $chart->id) ? 'selected' : ''}}
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
                        @isset($product->sizechart->id)
                        <img class="card-img" src="{{$product->sizechart->SZCT_URL}}" style="max-height:456; width:auto; height:auto;" alt="Card image">
                        <button type="button" onclick="confirmAndGoTo('{{url($removeChartURL)}}', 'unlink the Size Chart')" class="btn btn-danger mr-2">Unlink Size Chart</button>
                        @endisset
                    </div>
                </div>

                <div class="tab-pane" id="tags" role="tabpanel">
                    <div class="card-body">
                        <h4 class="card-title">Set Size Chart</h4>
                        <form class="form pt-3" method="post" action="{{ url($tagsFormURL) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>Select Model Tags</label>
                                <div class="input-group mb-3">
                                    <select class="select2 m-b-10 select2-multiple" style="width: 100%" multiple="multiple" data-placeholder="Choose From The Following Tags" name=tags[]>
                                        @foreach($tags as $tag)
                                        <option value="{{$tag->id}}"
                                            {{(in_array( $tag->id, $prodTagIDs)) ? 'selected' : ''}}
                                            >{{$tag->TAGS_NAME}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="text-danger">{{$errors->first('tags')}}</small>
                            </div>
                            <button type="submit" class="btn btn-success mr-2">Submit</button>
                        </form>
                    </div>
                </div>

                <div class="tab-pane" id="settings" role="tabpanel">
                    <div class="card-body">
                        <h4 class="card-title">{{ $formTitle }}</h4>
                        <form class="form pt-3" method="post" action="{{ url($formURL) }}" enctype="multipart/form-data">
                            @csrf
                            <input type=hidden name=id value="{{(isset($product)) ? $product->id : ''}}">

                            <div class="form-group">
                                <label>Category</label>
                                <div class="input-group mb-3">
                                    <select name=category class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                        <option value="" disabled selected>Pick From Categories</option>
                                        @foreach($categories as $categry)
                                        <option value="{{ $categry->id }}" @if(isset($product) && $categry->id == $product->PROD_SBCT_ID)
                                            selected
                                            @endif
                                            >{{$categry->category->CATG_NAME}} : {{$categry->SBCT_NAME}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="text-danger">{{$errors->first('category')}}</small>
                            </div>

                            <div class="form-group">
                                <label>Model Title</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Full Model Title" name=name value="{{ (isset($product)) ? $product->PROD_NAME : old('name')}}" required>
                                </div>
                                <small class="text-danger">{{$errors->first('name')}}</small>
                            </div>
                            <div class="form-group">
                                <label>Arabic Title</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name=arbcName placeholder="اسم الموديل بالعربيه" value="{{ (isset($product)) ? $product->PROD_ARBC_NAME : old('mail')}}"
                                        required>
                                </div>
                                <small class="text-danger">{{$errors->first('arbcName')}}</small>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" rows="3" required name=desc>{{(isset($product)) ? $product->PROD_DESC : old('desc')}}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Arabic Description</label>
                                <textarea class="form-control" rows="3" required name=arbcDesc>{{(isset($product)) ? $product->PROD_ARBC_DESC : old('desc')}}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Barcode</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Model Barcode" name=barCode value="{{ (isset($product)) ? $product->PROD_BRCD : old('barCode') }}">
                                </div>
                                @if($errors->first('barCode') !=null)
                                <small class="text-danger">{{$errors->first('barCode')}}</small>
                                @else
                                <small>Not Required</small>
                                @endif
                            </div>

                            <div class="form-group">
                                <label>Price</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" placeholder="Model Price" name=price value="{{ (isset($product)) ? $product->PROD_PRCE : old('price')}}" required>
                                </div>
                                <small class="text-danger">{{$errors->first('price')}}</small>
                            </div>

                            <div class="form-group">
                                <label>Cost</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" placeholder="Model Cost" name=cost value="{{ (isset($product)) ? $product->PROD_COST : old('cost') }}">
                                </div>
                                @if($errors->first('cost') !=null)
                                <small class="text-danger">{{$errors->first('cost')}}</small>
                                @else
                                <small>Not Required</small>
                                @endif
                            </div>

                            <div class="form-group">
                                <label>Offer Percentage</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" placeholder="Model Offer Percentage" name=offer value="{{ (isset($product)) ? $product->PROD_OFFR : old('offer') }}">
                                </div>
                                @if($errors->first('offer') != null)
                                <small class="text-danger">{{$errors->first('offer')}}</small>
                                @else
                                <small>Not Required - Default 0%</small>
                                @endif
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