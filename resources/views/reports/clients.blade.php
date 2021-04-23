@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="col-12">
            <h4 class="card-title">All Clients</h4>
            <h6 class="card-subtitle">List all clients registered on the system</h6>
            <div class="table-responsive m-t-5">
                <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='100' data-order="[]">
                    <thead>
                        <tr>
                            <th>Client Name</th>
                            <th>Client Mobn.</th>
                            <th>Area</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $total=0; ?>
                        @foreach ($items as $row)
                        <tr>
                            <td>{{$row->ORDR_GEST_NAME}}</td>
                            <td>{{$row->ORDR_GEST_MOBN}}</td>
                            <td>{{$row->AREA_NAME}}</td>
                            <td title="{{$row->ORDR_ADRS}}">{{(strlen($row->ORDR_ADRS) > 12) ? mb_substr($row->ORDR_ADRS,0,22, "utf-8") . "..." :  $row->ORDR_ADRS}}</td>
                        </tr>
                        @endforeach
                    </tbody>
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
                            title: 'Via Veneto',
                            footer: true,
                        }
                    ]
                });
            })
        })
</script>
@endsection