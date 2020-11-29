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
                            @if($detailed)
                            <th>36</th>
                            <th>38</th>
                            <th>40</th>
                            <th>42</th>
                            <th>44</th>
                            <th>46</th>
                            <th>48</th>
                            <th>50</th>
                            @endif
                            <th>Sold</th>
                            <th>Average Price</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $totalCount=0; ?>
                        <?php $totalPrice=0; ?>
                        @if($detailed)
                        <?php $total36=0; ?>
                        <?php $total38=0; ?>
                        <?php $total40=0; ?>
                        <?php $total42=0; ?>
                        <?php $total44=0; ?>
                        <?php $total46=0; ?>
                        <?php $total48=0; ?>
                        <?php $total50=0; ?>
                        @endif
                        @foreach ($items as $row)
                        <tr>
                            <td>{{$row->BRND_NAME}}</td>
                            <td>{{$row->MODL_NAME}}-{{$row->MODL_UNID}}</td>
                            @if($detailed)
                            <td>{{($row->total36 == null) ? 0 : $row->total36}}</td>
                            <td>{{($row->total38 == null) ? 0 : $row->total38}}</td>
                            <td>{{($row->total40 == null) ? 0 : $row->total40}}</td>
                            <td>{{($row->total42 == null) ? 0 : $row->total42}}</td>
                            <td>{{($row->total44 == null) ? 0 : $row->total44}}</td>
                            <td>{{($row->total46 == null) ? 0 : $row->total46}}</td>
                            <td>{{($row->total48 == null) ? 0 : $row->total48}}</td>
                            <td>{{($row->total50 == null) ? 0 : $row->total50}}</td>
                            <?php $total36+=($row->$total36??0); ?>
                            <?php $total38+=($row->$total38??0); ?>
                            <?php $total40+=($row->$total40??0); ?>
                            <?php $total42+=($row->$total42??0); ?>
                            <?php $total44+=($row->$total44??0); ?>
                            <?php $total46+=($row->$total46??0); ?>
                            <?php $total48+=($row->$total48??0); ?>
                            <?php $total50+=($row->$total50??0); ?>
                            @endif
                            <td>{{$row->soldCount}}</td>
                            <td>{{number_format($row->averagePrice)}}</td>
                            <td>{{number_format($row->totalSold)}}</td>
                            <?php $totalCount += $row->soldCount ?>
                            <?php $totalPrice += $row->totalSold ?>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td><strong> Total:</strong></td>
                            @if($detailed)
                            <td>{{$total36}}</td>
                            <td>{{$total38}}</td>
                            <td>{{$total40}}</td>
                            <td>{{$total42}}</td>
                            <td>{{$total44}}</td>
                            <td>{{$total46}}</td>
                            <td>{{$total48}}</td>
                            <td>{{$total50}}</td>
                            @endif
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