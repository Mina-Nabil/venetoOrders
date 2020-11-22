@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="col-12">
            <h4 class="card-title">Delivered Orders</h4>
            <h6 class="card-subtitle">List of orders between {{$start->format('d-M-Y')}} and {{$end->format('d-M-Y')}}</h6>
            <div class="table-responsive m-t-5">
                <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]">
                    <thead>
                        <tr>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Sold</th>
                            <th>Average Price</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $totalCount=0; ?>
                        <?php $totalPrice=0; ?>
                        @foreach ($items as $row)
                        <tr>
                            <td>{{$row->BRND_NAME}}</td>
                            <td>{{$row->MODL_NAME}}-{{$row->MODL_UNID}}</td>
                            <td>{{$row->soldCount}}</td>
                            <td>{{$row->averagePrice}}</td>
                            <td>{{$row->totalSold}}</td>
                            <?php $totalCount += $row->soldCount ?>
                            <?php $totalPrice += $row->totalSold ?>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td><strong> Total:</strong></td>
                            <td>{{number_format($totalCount)}}</td>
                            <td>{{number_format($totalPrice/$totalCount)}}</td>
                            <td><strong>{{number_format($totalPrice)}} </strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js_content')
<script>
    $(function () {
            $(function () {

                var table = $('#myTable').DataTable({
                    "displayLength": 25,
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'excel',
                            title: 'Whale Dashboard',
                            footer: true,
                        }
                    ]
                });
            })
        })
</script>
@endsection