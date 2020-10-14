@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="row ">
            <!-- Column -->
            <div class="col-md-6 col-lg-4 col-xlg-3">
                <div class="card">
                    <a href="{{url('orders/state/' . $type . '1')}}">
                        <div class="box bg-info text-center">
                            <h1 class="font-light text-white">{{$newCount}}</h1>
                            <h6 class="text-white">New</h6>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-4 col-xlg-3">
                <div class="card">
                    <a href="{{url('orders/state/' . $type . '2')}}">
                        <div class="box bg-warning text-center">
                            <h1 class="font-light text-white">{{$readyCount}}</h1>
                            <h6 class="text-white">Ready</h6>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-4 col-xlg-3">
                <div class="card">
                    <a href="{{url('orders/state/' . $type . '3')}}">
                        <div class="box bg-dark text-center">
                            <h1 class="font-dark text-light">{{$inDeliveryCount}}</h1>
                            <h6 class="text-white">In Delivery</h6>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Column -->
        </div>
    </div>

    <div class="col-12">
        <x-datatable id="myTable" :title="$title ?? 'Active Orders'" :subtitle="$subTitle ?? ''" :cols="$cols" :items="$items" :atts="$atts" :cardTitle="$cardTitle" />
    </div>
</div>
@endsection