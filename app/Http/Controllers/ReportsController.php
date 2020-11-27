<?php

namespace App\Http\Controllers;

use App\Models\Order;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ReportsController extends Controller
{

    function prepareSales($year = null)
    {
        $offlineArr = Order::getSalesChartData(0, $year);
        $onlineArr = Order::getSalesChartData(1, $year);
        $data['graphData'] = ['0' => $offlineArr['data'], '1' => $onlineArr['data']];
        $data['graphMax'] = max($offlineArr['max'], $onlineArr['max']);
        $data['graphTotal'] = [
            ["title" => "Total Online Sales", "value" => $onlineArr['total'],],
            ["title" => "Total Offline Sales", "value" => $offlineArr['total']]
        ];
        $data['chartTitle'] = "Sales Totals";
        $data['chartSubtitle'] = "Current Year Sales Summary";
        $data['formTitle'] = "Load Sales";
        $data['formSubtitle'] = "Load All Delivered Sales by Date";
        $data['formURL'] = url('reports/load/sales');
        $data['prepareURL'] = url('reports/prepare/sales');
        $data['years'] = Order::getOrderYears();
        $data['showDetails'] = false;
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

    function prepareInventory($year = null)
    {
        $data['years'] = Order::getOrderYears();

        $offlineArr = Order::getInventoryChartData(0, $year);
        $onlineArr = Order::getInventoryChartData(1, $year);
        $data['graphData'] = ['0' => $offlineArr['data'], '1' => $onlineArr['data']];
        $data['graphMax'] = max($offlineArr['max'], $onlineArr['max']);
        $data['graphTotal'] = [
            ["title" => "Items Sold Online", "value" => $onlineArr['total'],],
            ["title" => "Items Sold Offline", "value" => $offlineArr['total']]
        ];
        $data['chartTitle'] = "Sold Items Totals";
        $data['chartSubtitle'] = "Current Year Summary";
        $data['formTitle'] = "Load Sold Items";
        $data['formSubtitle'] = "Load All Delivered Items by Date";
        $data['formURL'] = url('reports/load/inventory');
        $data['prepareURL'] = url('reports/prepare/inventory');
        $data['showDetails'] = true;
        return view("reports.prepare", $data);
    }

    function inventory(Request $request)
    {
        $start = new DateTime($request->from);
        $end = new DateTime($request->to);
        if ($request->details)
            $data['items'] = Order::getInventoryReport($start, $end, $request->type);
        else
            $data['items'] = Order::getDetailedInventoryReport($start, $end, $request->type);
        $data['start'] = $start;
        $data['end']    = $end;
        $data['detailed'] = $request->details ?? false;
        return view('reports.inventory', $data);
    }
}
