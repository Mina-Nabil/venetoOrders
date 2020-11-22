@push('scripts')
    <link href="{{asset('assets/node_modules/chartist-js/dist/chartist.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/node_modules/chartist-js/dist/chartist-init.css')}}" rel="stylesheet">
    <link href="{{asset('assets/node_modules/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css')}}" rel="stylesheet">
    <link href="{{asset('assets/node_modules/css-chart/css-chart.css')}}" rel="stylesheet">
    <link href="{{asset('assets/node_modules/morrisjs/morris.css')}}" rel="stylesheet">
    <link href="{{asset('dist/css/pages/widget-page.css')}}" rel="stylesheet">
@endpush

<div>
    <div class="col-lg-12">
        <div class="card">
            <div class="row">
                <div class="col-lg-3 col-xlg-3 col-md-4 col-sm-12 b-r">
                    <div class="card-body">
                        <h3>{{$chartTitle}}</h3>
                        <h6 class="card-subtitle">{{$chartSubtitle}}</h6>
                        <div class="row">
                        
                            @foreach($totals as $total)
                            <div class="col-lg-12 m-t-40">
                                <h1 class="m-b-0 font-light">{{number_format($total['value'])}}</h1>
                                <h6 class="text-muted">{{$total['title']}}</h6>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-xlg-9 col-md-8 col-sm-12 align-self-center">
                    <div class="card-body">
                        <div class="user-analytics chartist-chart" style="height: 250px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script src="{{asset('assets/node_modules/chartist-js/dist/chartist.min.js')}}"></script>
<script src="{{asset('assets/node_modules/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js')}}"></script>
<script src="{{asset('assets/node_modules/sparkline/jquery.sparkline.min.js')}}"></script>
<script src="{{asset('assets/node_modules/echarts/echarts-all.js')}}"></script>

<script>
    new Chartist.Line('.user-analytics', {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', ' ']
        , series: [
       [ {{implode(', ', $graphs)}}]

      ]
    }, {
        high: '{{$max}}'
        , low: 0
        , showArea: true
        , lineSmooth: Chartist.Interpolation.simple({
            divisor: 100
        })
        , fullWidth: true
        , chartPadding: 0
        , plugins: [
        Chartist.plugins.tooltip()
      ], // As this is axis specific we need to tell Chartist to use whole numbers only on the concerned axis
        axisY: {
            onlyInteger: true
            , offset: 100
            , labelInterpolationFnc: function (value) {
                return (value / 1000) + 'k';
            }
        }
    });
</script>

@endpush