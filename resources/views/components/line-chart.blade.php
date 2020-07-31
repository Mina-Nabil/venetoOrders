@push('scripts')
    <link href="{{asset('assets/node_modules/chartist-js/dist/chartist.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/node_modules/chartist-js/dist/chartist-init.css')}}" rel="stylesheet">
    <link href="{{asset('assets/node_modules/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css')}}" rel="stylesheet">
    <link href="{{asset('assets/node_modules/css-chart/css-chart.css')}}" rel="stylesheet">
    <link href="{{asset('assets/node_modules/morrisjs/morris.css')}}" rel="stylesheet">
    <link href="{{asset('dist/css/pages/widget-page.css')}}" rel="stylesheet">
@endpush

<div>
    <div class="d-flex no-block align-items-center">
        <div>
            <h3>{{$chartTitle}}</h3>
            <h6 class="card-subtitle">{{$chartSubtitle}}</h6>
        </div>
        <div class="ml-auto">
            <ul class="list-inline">
                @foreach($graphs as $graph)
                <li>
                    <h6 class="text-muted"><i class="fa fa-circle m-r-5 text-{{$graph['color']}}"></i>{{$graph['name']}}</h6>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="total-revenue4" style="height: 350px;"></div>
        </div>
        <?php $arrayCount = count($totals) ?>
        <?php $width = ($arrayCount > 0) ? 12 / count($totals) : 0 ?>

        @foreach($totals as $total)
        <div class="col-lg-{{$width}} col-md-6 m-b-30 m-t-20 text-center">
            <h1 class="m-b-0 font-light">{{$total['value']}}{{$total['unit'] ?? ''}}</h1>
            <h6 class="text-muted">{{$total['name']}}</h6>
        </div>
        @endforeach
    </div>

</div>

@push('scripts')
<script src="{{asset('assets/node_modules/chartist-js/dist/chartist.min.js')}}"></script>
<script src="{{asset('assets/node_modules/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js')}}"></script>
<script src="{{asset('assets/node_modules/sparkline/jquery.sparkline.min.js')}}"></script>
<script src="{{asset('assets/node_modules/echarts/echarts-all.js')}}"></script>
<script>
$(function () {
    "use strict";
    // ============================================================== 
    // Total revenue chart
    // ============================================================== 
    new Chartist.Line('.total-revenue4', {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        , series: [
        [0, 2, 3.5, 0, 13, 1, 4, 1,12,14,8,6]
        , [8, 4, 5, 4, 16, 4, 3, 4,8,3,6,4,],
      ]
    }, {
        high: 15
        , low: 0
        , showArea: true
        , fullWidth: true
        , plugins: [
        Chartist.plugins.tooltip()
      ], // As this is axis specific we need to tell Chartist to use whole numbers only on the concerned axis
        axisY: {
            onlyInteger: true
            , offset: 20
            , labelInterpolationFnc: function (value) {
                return (value / 1) + 'k';
            }
        }
    });
});
</script>

@endpush