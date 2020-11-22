@extends('layouts.app')

@section("content")
<div class="col-12">
    <x-simple-chart chartTitle="{{$chartTitle}}" chartSubtitle="{{$chartSubtitle}}" :graphs="$graphData" :max="$graphMax" :totals="$graphTotal" />
</div>
@endsection