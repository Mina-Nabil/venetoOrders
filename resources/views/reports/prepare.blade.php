@extends('layouts.app')

@section("content")
<div class="col-12">
    <x-line-chart chartTitle="{{$chartTitle}}" chartSubtitle="{{$chartSubtitle}}" :graphs="$graphData" :max="$graphMax" :totals="$graphTotal" />
</div>
@endsection