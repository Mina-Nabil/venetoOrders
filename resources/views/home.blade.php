@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark text-light">{{$catgCardTitle}}</div>
                <div class="card-body">
                    <x-line-chart :chartTitle="$catgTitle" :chartSubtitle="$catgSubtitle" :graphs="$catgGraphs" :totals="$catgTotals"  />
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-dark text-light">{{$totalCardTitle}}</div>
                <div class="card-body">
                    <x-simple-chart :chartTitle="$totalTitle" :chartSubtitle="$totalSubtitle" :graphs="$totalGraphs" :totals="$totalTotals"  />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
