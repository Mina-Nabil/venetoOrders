@extends('layouts.app')

@section('content')
<div class="row">

    <div class="row m-t-40">
        <!-- Column -->
        <div class="col-md-6 col-lg-3 col-xlg-3">
            <div class="card">
                <div class="box bg-info text-center">
                    <h1 class="font-light text-white">2,064</h1>
                    <h6 class="text-white">Total Tickets</h6>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-md-6 col-lg-3 col-xlg-3">
            <div class="card">
                <div class="box bg-primary text-center">
                    <h1 class="font-light text-white">1,738</h1>
                    <h6 class="text-white">Responded</h6>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-md-6 col-lg-3 col-xlg-3">
            <div class="card">
                <div class="box bg-success text-center">
                    <h1 class="font-light text-white">1100</h1>
                    <h6 class="text-white">Resolve</h6>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-md-6 col-lg-3 col-xlg-3">
            <div class="card">
                <div class="box bg-dark text-center">
                    <h1 class="font-light text-white">964</h1>
                    <h6 class="text-white">Pending</h6>
                </div>
            </div>
        </div>
        <!-- Column -->
    </div>

    
    <div class="col-12">
        <x-datatable id="myTable"  :title="$title" :subtitle="$subTitle" :cols="$cols" :items="$items" :atts="$atts" />
    </div>
</div>
@endsection


