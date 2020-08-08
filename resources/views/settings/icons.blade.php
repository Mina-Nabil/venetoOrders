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
                <input type=hidden name=id value="{{(isset($icon)) ? $icon->id : ''}}">

                    <div class="form-group">
                        <label>Name</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Icon Name" name=name value="{{ (isset($icon)) ? $icon->ICON_NAME : old('name')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('name')}}</small>
                    </div>
            
                    <div class="form-group">
                        <label for="input-file-now-custom-1">Icon</label>
                          <div class="input-group mb-3">
                              <input type="file" id="input-file-now-custom-1" name=photo class="dropify" data-default-file="{{ (isset($icon->ICON_PATH)) ? asset( 'storage/'. $icon->ICON_PATH ) : old('photo') }}" />
                          </div>
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