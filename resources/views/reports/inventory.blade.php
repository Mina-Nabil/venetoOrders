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
                        @foreach ($items as $row)
                        <tr>
                            <td>{{$row->BRND_NAME}}</td>
                            <td>{{$row->MODL_NAME}}-{{$row->MODL_UNID}}</td>
                            @if($detailed)
                            <td>{{$row->total36 ?? 0}}</td>
                            <td>{{$row->total38 ?? 0}}</td>
                            <td>{{$row->total40 ?? 0}}</td>
                            <td>{{$row->total42 ?? 0}}</td>
                            <td>{{$row->total44 ?? 0}}</td>
                            <td>{{$row->total46 ?? 0}}</td>
                            <td>{{$row->total48 ?? 0}}</td>
                            <td>{{$row->total50 ?? 0}}</td>
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