@extends('layouts.app')

@section('content')



<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Categories Filters</h4>
                <h6 class="card-subtitle">Filter by Main Category</h6>
                <form class="form pt-3" method="post" action="{{ url($categoryURL) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label>Category</label>
                        <div class="input-group mb-3">
                            <select name=category class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                <option value="" disabled selected >Pick From Categories</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{$category->CATG_NAME}}</option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('category')}}</small>
                    </div>

                    <button type="submit" class="btn btn-success mr-2">Submit</button>
               
                </form>
                <hr>
                <h6 class="card-subtitle">Filter by Subcategory</h6>
                <form class="form pt-3" method="post" action="{{ url($subcategoryURL) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label>Sub Category</label>
                        <div class="input-group mb-3">
                            <select name=subcategory class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                <option value="" disabled selected >Pick From Categories</option>
                                @foreach($subcategories as $categry)
                                <option value="{{ $categry->id }}"
                                >{{$categry->category->CATG_NAME}} : {{$categry->SBCT_NAME}}</option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('subcategory')}}</small>
                    </div>

                    <button type="submit" class="btn btn-success mr-2">Submit</button>
               
                </form>
            </div>
        </div>
    </div>

</div>
@endsection