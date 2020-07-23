<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class InventoryController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth");
    }

    public function entry()
    {
        //models data
        $data['products'] = Product::all();
        $data['colors'] = Color::all();
        $data['sizes'] = Size::all();

        //form data
        $data['formURL'] = 'inventory/insert/entry';
        $data['formTitle'] = "New Stock Entry";

        return view("inventory.entry", $data);
    }

    public function insert(Request $request)
    {
        $entryArr = $this->getEntryArray($request);
        
        Inventory::insertEntry($entryArr );

        return redirect("inventory/current/stock");
    }

    public function stock()
    {

        $data['items'] = Inventory::with(["product", "color", "size"])->get();

        $data['title'] = "Stock List";
        $data['subTitle'] = "View Current Stock";
        $data['cols'] = ['Model', 'Color', 'Size', 'Count'];
        $data['atts'] = [ 
            ['foreignUrl' => ['products/details', 'INVT_PROD_ID', 'product', 'PROD_NAME']],
            ['foreign' => ['color','COLR_NAME']], 
            ['foreign' => ['size','SIZE_NAME']], 
            'INVT_CUNT'
        ];

        return view("inventory.stock", $data);
    }

    public function transactionDetails($code)
    {
        $data['items'] = Inventory::getTransactionByCode($code);
        abort_if(!isset($data['items'][0]), 404);
        $data['title'] = "Entry " .( (isset($data['items'][0]->INTR_CODE)) ? $data['items'][0]->INTR_CODE : "") .  " details";
        $data['subTitle'] = "Inventory Entry Details" . ((isset($data['items'][0]->DASH_USNM)) ? " done by '" . $data['items'][0]->DASH_USNM . "'": "") . 
        ((isset($data['items'][0]->INTR_DATE)) ? " on " . $data['items'][0]->INTR_DATE : "");
        $data['cols'] = ['Code', 'Product', 'Color', 'Size', 'In', 'Out'];
        $data['atts'] = [ 
            "INTR_CODE",
            ['attUrl' => ["url" => "products/profile", 'urlAtt'=>'INVT_PROD_ID', 'shownAtt'=>'PROD_NAME']], 
            'COLR_NAME', 
            'SIZE_NAME', 
            'INTR_IN',
            'INTR_OUT',
        ];

        return view("inventory.stock", $data);
    }

    public function transactions()
    {
        $data['items'] = Inventory::getGroupedTransactions();
        $data['title'] = "Latest Inventory Entries";
        $data['subTitle'] = "View the latest 500 inventory entries - Each Entry can be shown by the entry code";
        $data['cols'] = ['Code', 'Date', 'Done by', 'Total In', 'Total Out'];
        $data['atts'] = [ 
            ['attUrl' => ['url' =>'inventory/transaction', 'shownAtt' => 'INTR_CODE', 'urlAtt' => 'INTR_CODE']],
            "INTR_DATE", 
            'DASH_USNM', 
            'totalIn',
            'totalOut',
        ];

        return view("inventory.stock", $data);
    }


    private function getEntryArray($request)
    {
        $ret = array();

        for ($i = 0; isset($request->count[$i]); $i++) {
            $ret[$i] = [
                "modelID" => $request->model[$i],
                "colorID" => $request->color[$i],
                "sizeID" => $request->size[$i],
                "count" => $request->count[$i],
            ];
        }
        return $ret;
    }
}
