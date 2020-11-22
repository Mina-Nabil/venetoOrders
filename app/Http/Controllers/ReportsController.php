<?php

namespace App\Http\Controllers;

use App\Models\Order;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ReportsController extends Controller
{

    function prepareSales()
    {
        $offlineArr = Order::getSalesChartData(0);
        $onlineArr = Order::getSalesChartData(1);
        $data['graphData'] = ['0' => $offlineArr['data'], '1' => $onlineArr['data']];
        $data['graphMax'] = max($offlineArr['max'], $onlineArr['max']);
        $data['graphTotal'] = [
            ["title" => "Total Online Sales", "value" => $onlineArr['total'],],
            ["title" => "Total Offline Sales", "value" => $offlineArr['total']]
        ];
        $data['chartTitle'] = "Sales Totals";
        $data['chartSubtitle'] = "Current Year Sales Summary";
        $data['formURL'] = url('reports/load/sales');
        return view("reports.prepare", $data);
    }

    function sales(Request $request)
    {
        $start = new DateTime($request->from);
        $end = new DateTime($request->to);

        $data['items'] = Order::getSalesReport($start, $end, $request->type);
        $data['start'] = $start;
        $data['end']    = $end;
        return view('reports.sales', $data);
    }

    function prepareInventory()
    {
    }

    function inventory(Request $request)
    {
    }
}
