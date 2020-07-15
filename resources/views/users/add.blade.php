@extends('layouts.app')

@section('content')



<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $formTitle }}</h4>
                <form class="form pt-3" method="post" action="{{ url($formURL) }}" enctype="multipart/form-data">
                    @csrf
                    <input type=hidden name=id value="{{(isset($user)) ? $user->id : ''}}">
                    <div class="form-group">
                        <label>Full Name*</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Full Name" name=fullName value="{{ (isset($user)) ? $user->USER_FLNM : old('fullName')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('fullName')}}</small>
                    </div>
                    <div class="form-group">
                        <label>Email*</label>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" name=mail placeholder="User email" value="{{ (isset($user)) ? $user->USER_MAIL : old('mail')}}" required>
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
                        <small class="text-danger">{{$errors->first('mob')}}</small>
                    </div>



                    <div class="form-group">
                        <label>Area*</label>
                        <div class="input-group mb-3">
                            <select name=area class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                <option value="" disabled selected>Select Area</option>
                                @foreach($areas as $area)
                                <option value="{{ $area->id }}" @if(isset($user) && $area->id == $user->USER_USTP_ID)
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
                        <label>City*</label>
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
                    @if($isCancel)
                    <a href="{{url($homeURL) }}" class="btn btn-dark">Cancel</a>
                    @endif
                </form>
            </div>
        </div>
    </div>

</div>
@endsection