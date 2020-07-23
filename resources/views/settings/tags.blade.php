@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-7">
        <x-datatable id="myTable" :title="$title" :subtitle="$subTitle" :cols="$cols" :items="$items" :atts="$atts" />
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $formTitle }}</h4>
                <form class="form pt-3" method="post" action="{{ url($formURL) }}" enctype="multipart/form-data" >
                @csrf
                <input type=hidden name=id value="{{(isset($tag)) ? $tag->id : ''}}">

                    <div class="form-group">
                        <label>Tag Name*</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Tag Name" name=name value="{{ (isset($tag)) ? $tag->TAGS_NAME : old('name')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('name')}}</small>
                    </div>
                    <div class="form-group">
                        <label>Arabic Name*</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Arabic Name" name=arbcName value="{{ (isset($tag)) ? $tag->TAGS_ARBC_NAME : old('arbcName')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('arbcName')}}</small>
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