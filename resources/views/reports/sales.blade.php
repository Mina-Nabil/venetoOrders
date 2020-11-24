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
                            <th>#</th>
                            <th>Client</th>
                            <th>Area</th>
                            <th>Payment</th>
                            <th>Items #</th>
                            <th>Ordered</th>
                            <th>Delivered</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $total=0; ?>
                        @foreach ($items as $row)
                        <tr>
                            <td>{{$row->id}}</td>
                            <td>{{$row->ORDR_GEST_NAME}}</td>
                            <td>
                                <button type="button" style="padding:.1rem; width: auto" class="btn btn-secondary" data-container="body" title="{{$row->AREA_NAME}}" data-toggle="popover" data-placement="bottom"
                                    data-content="{{$row->ORDR_ADRS}}" data-original-title="Address:">{{$row->AREA_NAME}}</button></td>
                            <td>{{$row->PYOP_NAME}}</td>
                            <td>{{$row->itemsCount}}</td>
                            <td>{{(new DateTime($row->ORDR_OPEN_DATE))->format('d-M-Y')}}</td>
                            <td>{{(new DateTime($row->ORDR_DLVR_DATE))->format('d-M-Y')}}</td>
                            <td>{{number_format($row->ORDR_TOTL)}}</td>
                            <?php $total += $row->ORDR_TOTL ?>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><strong> Total:</strong></td>
                            <td><strong>{{number_format($total)}} </strong></td>
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