@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card card-body">
            <div class="text-right">
                <button id="print" class="btn btn-default btn-outline" type="button"> <span><i class="fa fa-print"></i> Print</span> </button>
            </div>
        </div>
        <div class="card card-body printableArea">
            <h3><strong><b>فاتوره</b> </strong><span class="pull-right">#{{($order->ORDR_SRNO > 0) ? $order->ORDR_SRNO : sprintf('%06d', $order->id)}}</span></h3>
            <hr>
            <div class="row">
                    <!-- <div class="col-lg-3">                 
                        <div class="pull-left text-left">
                            <address>
                                <div class="text-center">
                                    <img src="{{ asset('images/dark-logo.png') }}" height=40px alt="homepage" class="light-logo" />
                                </div>
                                <h4 class="font-bold">مصنع فيا فنيتو للملابس الجاهزه</h4>                     
                                <p class="text-muted m-l-30"></p>
                                </address>
                            </div>
                    </div> -->
                    <div class="col-lg-12">                 
                        <div class="pull-left text-right">
                            <address>
                                <h3>العميل</h3>
                                <h4 class="font-bold" style="font-size: 25px;">{{$order->ORDR_GEST_NAME}}</h4>
                                <p class="text-muted m-l-30">{{($order->ORDR_ADRS) ?? ''}}
                                    <br/> {{($order->ORDR_GEST_MOBN) ?? ''}}</p>
                                    <p class="m-t-30"><b>تاريخ الفاتوره :</b>  {{$order->created_at}}</p>
                                </address>
                            </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive color-bordered-table inverse-bordered-table m-t-40" style="clear: both;">
                        <table class="table table-hover text-right">
                            <thead>
                                <tr style="font-size: larger; border: 2px solid #141415;">
                                    <th style="border-bottom: 2px solid #141415;" class="text-right"><strong>اجمالي</strong></th>
                                    <th style="border-bottom: 2px solid #141415;" class="text-right"><strong>الكميه</strong></th>
                                    <th style="border-bottom: 2px solid #141415;" class="text-right"><strong>سعر الوحده</strong></th>
                                    <th style="border-bottom: 2px solid #141415;" class="text-center"><strong>المقاس</strong></th>
                                    <th style="border-bottom: 2px solid #141415;" class="text-right"><strong>الصنف</strong></thstyle="border-bottom: 2px solid #141415;">
                                </tr>
                            </thead>
                            <tbody>
                               <?php $i = 0; ?>   
                                @foreach($items as $item)
                                <tr style="border: 2px solid #141415;">
                                    <td class="text-right"><strong>{{number_format($item->ORIT_PRCE*$item->ORIT_CUNT, 2)}}</strong></td>
                                    <td class="text-right"><strong>{{$item->ORIT_CUNT}}</strong></td>
                                    <td class="text-right"><strong>{{$item->ORIT_PRCE}}</strong></td>
                                    <td class="text-center"><strong>{{$item->ORIT_SIZE}}</strong></td>
                                    <td class="text-right"><strong>{{$item->BRND_NAME}}-{{$item->MODL_UNID}}</strong></td>
                                </tr>
                               <?php $i++; ?> 
                                @endforeach

                                @for ($i ; $i< 15 ; $i++)
                                <tr style="font-size: larger;border: 2px solid #141415;">
                                    <td><div class="m-t-25"></div></td>
                                    <td><div class="m-t-25"></div></td>
                                    <td><div class="m-t-25"></div></td>
                                    <td><div class="m-t-25"></div></td>
                                    <td><div class="m-t-25"></div></td>
                                </tr>
                                @endfor

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="pull-right m-t-30 text-left">
                        <p style="padding-left: 110px; font-size: 25px;"><strong> اجمالي الفاتوره: &ensp;{{number_format($order->ORDR_TOTL, 2)}} </strong></p>
                    </div>
                    <div class="pull-right m-t-30 text-left">
                        <p style="padding-left: 110px; font-size: 25px;"><strong> مصاريف شحن : &ensp;{{number_format($order->AREA_RATE, 2)}} </strong></p>
                    </div>
                    <div class="pull-right m-t-30 text-right">
                        <hr>
                        <h3><b>اجمالي :</b> {{$totalInArabic}}</h3>
                    </div>
                    <div class="clearfix"></div>
                    <hr>    
                    <div class="row " style="margin-top: 60;">
                      <div class="col-lg-12">
                          <div class="pull-left text-right">
                              <address>
                                  <div class="row ">
                                      <div class="pull-left text-center" style="width: 50%; font-size: x-large;">
                                        <strong>
                                            المستلم 
                                        </strong>
                                      </div>

                                      <div class="pull-right text-center" style="width: 50%; font-size: x-large;">
                                        <strong>
                                            الحسابات 
                                        </strong>
                                      </div>
                                  </div>
                                </address>
                            </div>
                                
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


@endsection