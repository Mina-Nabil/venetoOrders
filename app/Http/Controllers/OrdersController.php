<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Driver;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentOption;
use App\Models\Product;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public $data;
    public $homeURL = "orders/acrive";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function active()
    {
        $this->initTableArr(1);
        $this->data['newCount'] = Order::getOrdersCountByState(1);
        $this->data['readyCount'] = Order::getOrdersCountByState(2);
        $this->data['inDeliveryCount'] = Order::getOrdersCountByState(3);
        return view("orders.active", $this->data);
    }
    public function state(int $stateID)
    {
        $this->initTableArr(false, $stateID);
        if ($stateID > 0 && $stateID < 4) {
            $this->data['newCount'] = Order::getOrdersCountByState(1);
            $this->data['readyCount'] = Order::getOrdersCountByState(2);
            $this->data['inDeliveryCount'] = Order::getOrdersCountByState(3);
        } elseif ($stateID > 3 && $stateID < 6) {
            $this->data['deliveredCount'] = Order::getOrdersCountByState(4);
            $this->data['cancelledCount'] = Order::getOrdersCountByState(5);
            return view("orders.history", $this->data);
        } else {
            abort(404);
        }
        return view("orders.active", $this->data);
    }

    public function monthly(int $stateID = -1)
    {
        $this->initTableArr(false, $stateID, date('m'), date('Y'));
        $this->data['deliveredCount'] = Order::getOrdersCountByState(4);
        $this->data['cancelledCount'] = Order::getOrdersCountByState(5);
        $this->data['historyURL'] = "orders/month";
        return view("orders.history", $this->data);
    }

    public function loadHistory()
    {
        $data['years'] = Order::selectRaw('YEAR(ORDR_OPEN_DATE) as order_year')->distinct()->get();
        return view("orders.prepareHistory", $data);
    }

    public function history($year, $month, $state = -1)
    {
        $this->initTableArr(false, $state, $month, $year);
        $startDate  = $this->getStartDate($month, $year);
        $endDate    = $this->getEndDate($month, $year);
        $this->data['historyURL'] = url('orders/history/' . $year . '/' . $month);
        $this->data['deliveredCount'] = Order::getOrdersCountByState(4, $startDate, $endDate);
        $this->data['cancelledCount'] = Order::getOrdersCountByState(5, $startDate, $endDate);
        return view("orders.history", $this->data);
    }

    public function addNew()
    {
        $this->data['inventory']    =   Inventory::with("color", "size", "product")->where("INVT_CUNT", ">", 0)->get();
        $this->data['areas']        =   Area::where('AREA_ACTV', 1)->get();
        $this->data['users']        =   User::all();
        $this->data['payOptions']   =  PaymentOption::all();
        $this->data['formTitle'] = "Add New Order";
        $this->data['formURL'] = "orders/insert";
        $this->data['isCancel'] = true;
        $this->data['homeURL']  = $this->homeURL;

        return view("orders.add", $this->data);
    }
    //////////////////////////////Order Details Page and Functions//////////////////////////////////////////
    public function details($id)
    {
        $data = Order::getOrderDetails($id); //returns order Array and Items Array

        //Status Panel
        $data['isOrderReady'] = true;
        foreach ($data['items'] as $item)
            if (!$item->ORIT_VRFD) {
                $data['isOrderReady'] = false;
                break;
            }
        $data['setOrderNewUrl']           =   url('orders/set/new/' . $data['order']->id);
        $data['setOrderReadyUrl']           =   url('orders/set/ready/' . $data['order']->id);
        $data['setOrderInDeliveryUrl']      =   url('orders/set/indelivery/' . $data['order']->id);
        $data['setOrderCancelledUrl']      =   url('orders/set/cancelled/' . $data['order']->id);
        $data['setOrderDeliveredUrl']      =   url('orders/set/delivered/' . $data['order']->id);

        //Add Items Panel
        $data['inventory']      =   Inventory::with("color", "size", "product")->where("INVT_CUNT", ">", 0)->get();
        $data['isCancel']       =   false;
        $data['addFormURL']     =   url('orders/add/items/' . $id);

        //Driver Panel
        $data['drivers']      =   Driver::all();
        $data['assignDriverFormURL']     =   url('orders/assign/driver');

        //Payment Panel
        $data['paymentURL']             =   url('orders/collect/payment');
        $data['deliveryPaymentURL']     =   url('orders/collect/delivery');
        $data['discountURL']            =   url('orders/set/discount');
        
        //Edit Info Panel
        $data['areas']                  = Area::where('AREA_ACTV', 1)->get();
        $data['editInfoURL']             =   url('orders/edit/details');

        $data['remainingMoney']         =   $data['order']->ORDR_TOTL - $data['order']->ORDR_PAID - $data['order']->ORDR_DISC;

        return view("orders.details", $data);
    }

    public function insertNewItems($orderID, Request $request)
    {
        $order = Order::findOrFail($orderID);
        DB::transaction(function () use ($order, $request) {
            $orderItemArray = $this->getOrderItemsArray($request);
            foreach ($orderItemArray as $item) {
                $orderItem = $order->order_items()->firstOrNew(
                    ['ORIT_INVT_ID' => $item['ORIT_INVT_ID']]
                );
                $orderItem->ORIT_CUNT += $item['ORIT_CUNT'];
                $orderItem->ORIT_VRFD = 0;
                $inventory = Inventory::findOrFail($item['ORIT_INVT_ID']);
                $inventory->INVT_CUNT -= $item['ORIT_CUNT'];
                $orderItem->save();
                $inventory->save();
            }
            $order->recalculateTotal();
        });
        return redirect("orders/details/" . $order->id);
    }

    public function setReady($id)
    {

        $order = Order::findOrFail($id);
        DB::transaction(function () use ($order) {
            $isReady = true;
            foreach ($order->order_items as $item) {
                if ($item->ORIT_VRFD == '0') {
                    $isReady = false;
                    break;
                }
            }
            if ($isReady) {
                $order->ORDR_STTS_ID = 2;
                $order->save();
            }
        });
        return redirect("orders/details/" . $order->id);
    }

    public function setCancelled($id)
    {

        $order = Order::findOrFail($id);
        DB::transaction(function () use ($order) {
            $isReturned = true;
            foreach ($order->order_items as $item) {
                $inventory = Inventory::findOrfail($item->ORIT_INVT_ID);
                $inventory->INVT_CUNT += $item->ORIT_CUNT;
                if (!$inventory->save()) {
                    $isReturned = false;
                    break;
                }
            }
            if ($isReturned) {
                $order->ORDR_STTS_ID = 5;
                $order->ORDR_PAID = 0;
                $order->ORDR_DLVR_DATE = date('Y-m-d H:i:s');
                $order->ORDR_DRVR_ID = null;
                $order->save();
            }
        });
        return redirect("orders/details/" . $order->id);
    }

    public function setNew($id)
    {

        $order = Order::findOrFail($id);
        $order->ORDR_STTS_ID = 1;
        $order->save();
        return redirect("orders/details/" . $order->id);
    }

    public function setInDelivery($id)
    {
        $order = Order::findOrFail($id);
        if ($order->ORDR_STTS_ID == 2 && isset($order->driver) && $order->driver->DRVR_ACTV) {
            $order->ORDR_STTS_ID = 3;
            $order->save();
        }
        return redirect("orders/details/" . $order->id);
    }

    public function setDelivered($id)
    {
        $order = Order::findOrFail($id);
        $remainingMoney = $order->ORDR_TOTL - $order->ORDR_DISC - $order->ORDR_PAID;
        if ($order->ORDR_STTS_ID == 3 && $remainingMoney == 0) {
            $order->ORDR_STTS_ID = 4;
            $order->save();
        }
        return redirect("orders/details/" . $order->id);
    }

    public function assignDriver(Request $request)
    {
        $request->validate([
            'id' => "required",
            'driver' => "required|exists:drivers,id"
        ]);

        $order = Order::findOrFail($request->id);
        if ($order->ORDR_STTS_ID < 3) { // New or ready
            $order->ORDR_DRVR_ID = $request->driver;
            $order->save();
        }
        return redirect("orders/details/" . $order->id);
    }

    public function collectNormalPayment(Request $request)
    {
        $order = Order::findOrFail($request->id);
        $request->validate([
            'id' => "required",
            'payment' => "required|min:0|max:" . $order->ORDR_TOTL
        ]);
        if($order->ORDR_STTS_ID < 4) {
            $order->ORDR_PAID += $request->payment;
            $order->save();
        }
        return redirect("orders/details/" . $order->id);
    }

    public function collectDeliveryPayment(Request $request)
    {
        $order = Order::findOrFail($request->id);
        $request->validate([
            'id' => "required",
            'deliveryPaid' => "required|min:0"
        ]);


        $order->ORDR_DLFE = $request->deliveryPaid;
        $order->save();
        return redirect("orders/details/" . $order->id);
    }

    public function setDiscount(Request $request)
    {
        $order = Order::findOrFail($request->id);
        $request->validate([
            'id' => "required",
            'discount' => "required|min:0|max:" . $order->ORDR_TOTL
        ]);

        if($order->ORDR_STTS_ID < 4) {
        $order->ORDR_DISC += $request->discount;
        $order->save();
        }
        return redirect("orders/details/" . $order->id);
    }

    public function toggleItem($id)
    {

        $item = OrderItem::findOrfail($id);
        $order = Order::findOrfail($item->ORIT_ORDR_ID);
        if ($order->ORDR_STTS_ID != 1) { //still new
            return 'failed';
        }
        if ($item->ORIT_VRFD) {
            $item->ORIT_VRFD = 0;
        } else {
            $item->ORIT_VRFD = 1;
        }
        return (($item->save()) ? 1 : 'failed');
    }

    public function deleteItem($id)
    {

        $item = OrderItem::findOrfail($id);
        $order = Order::findOrfail($item->ORIT_ORDR_ID);
        DB::transaction(function () use ($order, $item) {
            if ($order->ORDR_STTS_ID != 1) { //still new
                return 'failed';
            }
            $inventory = Inventory::findOrFail($item->ORIT_INVT_ID);
            $inventory->INVT_CUNT += $item->ORIT_CUNT;
            $item->delete();
            $inventory->save();
            $order->recalculateTotal();
        });
        return redirect("orders/details/" . $order->id);
    }

    public function changeQuantity(Request $request)
    {
        $request->validate([
            "itemID" => "required",
            "count" => "required|numeric|min:0"
        ]);
        $orderItem = OrderItem::findOrFail($request->itemID);
        $order = Order::findOrfail($orderItem->ORIT_ORDR_ID);
        DB::transaction(function () use ($order, $orderItem, $request) {
            if ($order->ORDR_STTS_ID != 1) { //still new
                return redirect("orders/details/" . $orderItem->ORIT_ORDR_ID);
            }
            $orderItem->ORIT_CUNT = $request->count;
            $orderItem->ORIT_VRFD = 0;
            $orderItem->save();
            $order->recalculateTotal();
        });
        return redirect("orders/details/" . $orderItem->ORIT_ORDR_ID);
    }

    public function editOrderInfo(Request $request)
    {
        $request->validate([
            "id" => "required",

        ]);
        $order = Order::findOrfail($request->od);
        $order->ORDR_ADRS = $request->address;
        $order->ORDR_NOTE = $request->note;
        $order->ORDR_AREA_ID = $request->area;
        return redirect("orders/details/" . $order->id);
    }

    ////////////////////////////Insert Order from dashboard///////////////////////////

    public function insert(Request $request)
    {

        $request->validate([
            "user"          =>  "required_if:guest,2|nullable|exists:users,id",
            "guestName"     =>  "required_if:guest,1",
            "guestMob"      =>  "required_if:guest,1",
            "area"          =>  "required",
            "option"        =>  "required",
            "address"      =>  "required"
        ]);

        $order = new Order();
        if (isset($request->user))
            $order->ORDR_USER_ID = $request->user;
        else {
            $order->ORDR_GEST_NAME = $request->guestName;
            $order->ORDR_GEST_MOBN = $request->guestMob;
        }
        $order->ORDR_OPEN_DATE = date('Y-m-d H:i:s');
        $order->ORDR_ADRS = $request->address;
        $order->ORDR_NOTE = $request->note;
        $order->ORDR_AREA_ID = $request->area;
        $order->ORDR_PYOP_ID = $request->option;
        $order->ORDR_STTS_ID = 1; // new order

        $orderItemArray = $this->getOrderItemsObjectArray($request);
        $order->ORDR_TOTL = $this->getOrderTotal($request);

        $order->save();
        $order->order_items()->saveMany($orderItemArray);
        foreach ($orderItemArray as $item) {
            $inventory = Inventory::findOrFail($item->ORIT_INVT_ID);
            $inventory->INVT_CUNT -= $item->ORIT_CUNT;
            $inventory->save();
        }

        return redirect("orders/details/" . $order->id);
    }


    /***
     * 
     * @param $isActive int
     * if active = 1 , history = 2
     * @param $state
     * 1 New - 2 Ready - 3 In Delivery - 4 Delivered - 5 Cancelled - 6 Returned
     * @param $year int
     * if history set year e.g 2020
     * 
     */
    private function initTableArr($isActive, $state = -1, $month = -1, $year = -1)
    {
        if ($isActive == 1)
            $this->data['items']    = Order::getActiveOrders();
        elseif ($month == -1 && $year == -1) {
            $this->data['items']    = Order::getActiveOrders($state);
        } else {
            $this->data['items']    = Order::getOrdersByDate(true, $month, $year, $state);
        }
        $this->data['cardTitle'] = true;
        $this->data['cols'] = ['id', 'Client', 'Status', 'Area', 'Payment',  'Items', 'Ordered On', 'Total'];
        $this->data['atts'] = [
            ['attUrl' => ['url' => "orders/details", "shownAtt" => 'id', "urlAtt" => 'id']],
            ['urlOrStatic' => ['url' => "users/profile", "shownAtt" => 'USER_NAME', "urlAtt" => 'ORDR_USER_ID', 'static' => 'ORDR_GEST_NAME']],
            [
                'stateQuery' => [
                    "classes" => [
                        "1" => "label-info",
                        "2" => "label-warning",
                        "3" =>  "label-dark bg-dark",
                        "4" =>  "label-success",
                        "5" =>  "label-danger",
                    ],
                    "att"           =>  "ORDR_STTS_ID",
                    'foreignAtt'    => "STTS_NAME",
                    'url'           => "orders/details/",
                    'urlAtt'        =>  'id'
                ]
            ],
            'AREA_NAME',
            'PYOP_NAME',
            'itemsCount',
            'ORDR_OPEN_DATE',
            'ORDR_TOTL'
        ];
    }

    private function getOrderItemsArray(Request $request)
    {
        $retArr = array();
        foreach ($request->item as $index => $item) {
            array_push(
                $retArr,
                ["ORIT_INVT_ID" => $item, "ORIT_CUNT" => $request->count[$index]]
            );
        }
        return $retArr;
    }

    private function getOrderItemsObjectArray(Request $request)
    {
        $retArr = array();
        foreach ($request->item as $index => $item) {
            array_push($retArr, new OrderItem(
                ["ORIT_INVT_ID" => $item, "ORIT_CUNT" => $request->count[$index]]
            ));
        }
        return $retArr;
    }

    private function getOrderTotal(Request $request)
    {
        $total = 0;
        foreach ($request->item as $index => $item) {
            $product = Inventory::with("product")->findOrFail($item)->product;
            $price = $product->PROD_PRCE - $product->PROD_OFFR;
            $total += $request->count[$index] * $price;
        }
        return $total;
    }

    ///////////////Helper Functions
    private function getStartDate($month, $year)
    {
        $retDate = null;
        if ($month == -1) {
            $retDate = $year . '-01-01 00:00:00';
        } else {
            $retDate = $year . '-' . $month . '-01 00:00:00';
        }
        return $retDate;
    }

    private function getEndDate($month, $year)
    {
        $retDate = null;
        if ($month == -1) {
            $retDate = $year . '-12-31 23:59:59';
        } else {
            $retDate = (new DateTime($year . '-' . $month . '-01'))->format('Y-m-t 23:59:59');
        }
        return $retDate;
    }
}
