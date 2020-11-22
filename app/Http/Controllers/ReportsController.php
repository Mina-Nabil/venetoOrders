<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ReportsController extends Controller
{

    function prepareSales()
    {
        $offlineArr = Order::getSalesChartData(0);
        $onlineArr = Order::getSalesChartData(1);
        $data['graphData'] =['0' => $offlineArr['data'],'1' => $onlineArr['data']];
        $data['graphMax'] = max($offlineArr['max'], $onlineArr['max'] );
        $data['graphTotal'] = [
           [ "title" => "Total Online Sales", "value" => $onlineArr['total'],],
           [ "title" => "Total Offline Sales", "value" => $offlineArr['total']]
        ];
        $data['chartTitle'] = "Sales Totals";
        $data['chartSubtitle'] = "Current Year Sales Summary";

        return view("reports.prepare", $data);
    }

    function sales(Request $sales)
    {
    }

    function prepareInventory()
    {
    }

    function inventory(Request $request)
    {
    }
}
