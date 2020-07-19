@extends('layouts.app')

@section('content')



<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $formTitle }}</h4>
                <form class="form pt-3" method="post" action="{{ url($formURL) }}" enctype="multipart/form-data">
                    @csrf
                    <input type=hidden name=id value="{{(isset($product)) ? $product->id : ''}}">

                    <div class="form-group">
                        <label>Category</label>
                        <div class="input-group mb-3">
                            <select name=category class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                <option value="" disabled selected >Pick From Categories</option>
                                @foreach($categories as $categry)
                                <option value="{{ $categry->id }}"
                                @if(isset($product) && $categry->id == $product->PROD_SBCT_ID)
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
                            <input type="text" class="form-control" name=arbcName placeholder="اسم الموديل بالعربيه" value="{{ (isset($product)) ? $product->PROD_ARBC_NAME : old('mail')}}" required>
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
                    @if($isCancel)
                    <a href="{{url($homeURL) }}" class="btn btn-dark">Cancel</a>
                    @endif
                </form>
            </div>
        </div>
    </div>

</div>
@endsection