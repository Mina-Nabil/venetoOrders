<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ReportsController extends Controller
{

    function prepareSales()
    {
        $chartArr = Order::getSalesChartData();
        $data['graphData'] = $chartArr['data'];
        $data['graphMax'] = $chartArr['max'];
        $data['graphTotal'] = [[
            "title" => "Total Sales", "value" => $chartArr['total']
        ]];
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
