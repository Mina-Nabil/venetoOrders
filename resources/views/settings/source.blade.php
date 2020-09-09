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
                <form class="form pt-3" method="post" action="{{ url($formURL) }}" enctype="multipart/form-data">
                    @csrf
                    <input type=hidden name=id value="{{(isset($source)) ? $source->id : ''}}">

                    <div class="form-group">
                        <label>Source Name*</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Source Name" name=name value="{{ (isset($source)) ? $source->ORSC_NAME : old('name')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('name')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Client Account</label>
                        <div class="input-group mb-3">
                            <select name=client class="select2 form-control custom-select" style="width: 100%; height:36px;">
                                <option value="" disabled selected>Pick From Clients</option>
                                @foreach($clients as $client)
                                <option value="{{ $client->id }}" @if(old('client')==$client->id)
                                    selected
                                    @elseif(isset($source) && $source->ORSC_CLNT_ID == $client->id)
                                    selected
                                    @endif
                                    >
                                    {{$client->CLNT_SRNO}} - {{$client->CLNT_NAME}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('client')}}</small>
                    </div>



                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    @if($isCancel)
                    <a href="{{url($homeURL) }}" class="btn btn-dark">Cancel</a>
                    @endif
                    <button type="button" onclick="confirmAndGoTo('{{url('sources/feed')}}', 'Add all missing Clients as Order Sources')" class="btn btn-success mr-2">Add All New Clients</button>
                </form>
            </div>
        </div>
    </div>

</div>
<script>
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