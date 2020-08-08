@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="row ">
            <!-- Column -->
            <div class="col-md-6 col-lg-4 col-xlg-3">
                <div class="card">
                    <a href="{{url($historyURL . '/4')}}">
                        <div class="box bg-success text-center">
                            <h1 class="font-light text-white">{{$deliveredCount}}</h1>
                            <h6 class="text-white">Delivered</h6>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-4 col-xlg-3">
                <div class="card">
                    <a href="{{url($historyURL . '/5')}}">
                        <div class="box bg-danger text-center">
                            <h1 class="font-light text-white">{{$cancelledCount}}</h1>
                            <h6 class="text-white">Cancelled</h6>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-4 col-xlg-3">
                <div class="card">
                    <a href="{{url($historyURL . '/6')}}">
                        <div class="box bg-primary text-center">
                            <h1 class="font-light text-white">{{$returnedCount}}</h1>
                            <h6 class="text-white">Returned</h6>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <x-datatable id="myTable" :title="$title ?? 'Orders List'" :subtitle="$subTitle ?? ''" :cols="$cols" :items="$items" :atts="$atts" :cardTitle="$cardTitle" />
    </div>
</div>
@endsection