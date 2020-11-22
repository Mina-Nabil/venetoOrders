@extends('layouts.app')
<script>
    function prepareSales(){

        let yearSel = document.getElementById('year');

        let selectedYear    = yearSel.options[yearSel.selectedIndex].value;
    
        window.location.href = '{{$prepareURL}}' + '/' + selectedYear ;
    
    }
</script>
@section("content")
<div class="col-12">
    <div class="card card-body">
        <div class=row>
            <div class=col-9>
                <div class="form-group">
                    <label>Year</label>
                    <div class="input-group mb-3">
                        <select id=year class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                            @foreach ($years as $year)
                            @isset($year->orderYear)
                            <option value="{{$year->orderYear}}">{{$year->orderYear}}</option>
                            @endisset
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-3  align-self-center">
                <button onclick="prepareSales()" class="btn btn-success mr-2">Load Graph</button>
            </div>
        </div>
    </div>
    <x-line-chart chartTitle="{{$chartTitle}}" chartSubtitle="{{$chartSubtitle}}" :graphs="$graphData" :max="$graphMax" :totals="$graphTotal" />
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{$formTitle}}</h4>
                <h6 class="card-subtitle">{{$formSubtitle}}</h6>
                <form class="form pt-3" method="post" action="{{ url($formURL) }}">
                    @csrf
                    <div class="form-group">
                        <label>Sales Type*</label>
                        <div class="input-group mb-3">
                            <select name=type class="select form-control custom-select" style="width: 100%; height:36px;" required>
                                <option value="-1" selected>All Sales</option>
                                <option value="1">Online</option>
                                <option value="0">Offline</option>
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('gender')}}</small>
                    </div>
                    <div class=row>
                        <div class="col-6 form-group">
                            <label>From*</label>
                            <div class="input-group mb-3">
                                <input type="date" class="form-control" name=from required>
                            </div>
                        </div>


                        <div class="col-6 form-group">
                            <label>To*</label>
                            <div class="input-group mb-3">
                                <input type="date" class="form-control" name=to required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mr-2">Submit</button>

                </form>
            </div>
        </div>
    </div>

</div>
@endsection