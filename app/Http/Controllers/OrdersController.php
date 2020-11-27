<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Client;
use App\Models\Driver;
use App\Models\Finished;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderSource;
use App\Models\PaymentOption;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use convert_ar;

class OrdersController extends Controller
{
    public $data;
    public $homeURL = "orders/acrive";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function active($type = 1)
    {
        $this->initTableArr(1, -1, -1, -1, $type);
        $this->data['newCount'] = Order::getOrdersCountByState(1, null, null, $type);
        $this->data['readyCount'] = Order::getOrdersCountByState(2, null, null, $type);
        $this->data['inDeliveryCount'] = Order::getOrdersCountByState(3, null, null, $type);
    
        return view("orders.active", $this->data);
    }
    public function state(int $type, int $stateID)
    {
        $this->initTableArr(false, $stateID, -1, -1, $type);
        if ($stateID > 0 && $stateID < 4) {
            $this->data['newCount'] = Order::getOrdersCountByState(1, null, null, $type);
            $this->data['readyCount'] = Order::getOrdersCountByState(2, null, null,  $type);
            $this->data['inDeliveryCount'] = Order::getOrdersCountByState(3, null, null,  $type);
        } elseif ($stateID > 3 && $stateID < 7) {
            $this->data['deliveredCount'] = Order::getOrdersCountByState(4,  null, null, $type);
            $this->data['cancelledCount'] = Order::getOrdersCountByState(5, null, null,  $type);
            $this->data['returnedCount'] = Order::getOrdersCountByState(6, null, null,  $type);
            return view("orders.history", $this->data);
        } else {
            abort(404);
        }
        return view("orders.active", $this->data);
    }

    public function monthly(int $type, int $stateID = -1)
    {
        $this->initTableArr(false, $stateID, date('m'), date('Y'), $type);
        $startDate  = $this->getStartDate(date('m'), date('Y'), $type);
        $endDate    = $this->getEndDate(date('m'), date('Y'), $type);
        $this->data['deliveredCount'] = Order::getOrdersCountByState(4, $startDate, $endDate, $type);
        $this->data['cancelledCount'] = Order::getOrdersCountByState(5, $startDate, $endDate, $type);
        $this->data['returnedCount'] = Order::getOrdersCountByState(6, $startDate, $endDate, $type);
        $this->data['historyURL'] = "orders/month/" . $type ;
        return view("orders.history", $this->data);
    }

    public function loadHistory($type)
    {
        $data['years'] = Order::selectRaw('YEAR(ORDR_OPEN_DATE) as order_year')->distinct()->get();
        $data['type']   = $type;
        return view("orders.prepareHistory", $data);
    }

    public function history($type, $year, $month, $state = -1)
    {
        $this->initTableArr(false, $state, $month, $year, $type);
        $startDate  = $this->getStartDate($month, $year);
        $endDate    = $this->getEndDate($month, $year);
        $this->data['historyURL'] = url('orders/history/' . $type . '/' . $year . '/' . $month);
        $this->data['deliveredCount'] = Order::getOrdersCountByState(4, $startDate, $endDate, $type);
        $this->data['cancelledCount'] = Order::getOrdersCountByState(5, $startDate, $endDate, $type);
        $this->data['returnedCount'] = Order::getOrdersCountByState(6, $startDate, $endDate, $type);
        return view("orders.history", $this->data);
    }

    public function addNew($type) //Type is online or offline
    {
        $this->data['finished']    =   Finished::getAllFinished();
        $this->data['areas']       =   Area::where('AREA_ACTV', 1)->get();
        $this->data['sources']     =   OrderSource::all();
        $this->data['payOptions']  =  PaymentOption::all();
        $this->data['formTitle'] = "Add New Order";
        $this->data['formURL'] = "orders/insert/" . $type;
        $this->data['isCancel'] = true;
        $this->data['isOnline'] = ($type == 1);
        $this->data['homeURL']  = $this->homeURL;

        return view("orders.add", $this->data);
    }
    //////////////////////////////Order Details Page and Functions//////////////////////////////////////////
    public function details($id)
    {
        $data = Order::getOrderDetails($id); //returns order Array and Items Array
        if (!isset($data['order']->ORDR_STTS_ID)) abort(404);

        //Status Panel
        $data['isOrderReady'] = true;
        foreach ($data['items'] as $item)
            if (!$item->ORIT_VRFD) {
                $data['isOrderReady'] = false;
                break;
            }
        $data['isPartiallyReturned']    =   (($data['order']->ORDR_STTS_ID == 4 || $data['order']->ORDR_STTS_ID == 3) && isset($data['order']->ORDR_RTRN_ID) && is_numeric($data['order']->ORDR_RTRN_ID));
        $data['isFullyReturned']        =   ($data['order']->ORDR_STTS_ID == 6);
        $data['isCancelled']        =   ($data['order']->ORDR_STTS_ID == 5);

        $data['setOrderNewUrl']             =   url('orders/set/new/' . $data['order']->id);
        $data['setOrderReadyUrl']           =   url('orders/set/ready/' . $data['order']->id);
        $data['setOrderInDeliveryUrl']      =   url('orders/set/indelivery/' . $data['order']->id);
        $data['setOrderCancelledUrl']       =   url('orders/set/cancelled/' . $data['order']->id);
        $data['setOrderDeliveredUrl']       =   url('orders/set/delivered/' . $data['order']->id);
        $data['linkNewReturnUrl']           =   url('orders/create/return/' . $data['order']->id);
        $data['returnUrl']                  =   url('orders/return/' . $data['order']->id);

        //Usertype
        $data['userType']       = Auth::user()->DASH_TYPE_ID;
        $data['isAdmin']        = ($data['userType'] == 1);
        $data['isInventory']    = ($data['userType'] == 2) || $data['isAdmin'];
        $data['isSales']        = ($data['userType'] == 3) || $data['isAdmin'];

        //Add Items Panel
        $data['finished']      =   Finished::getAllFinished();
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

        //remaining money
        $data['isOffline'] = $data['order']->ORDR_ONLN == 2;
        $data['remainingMoney']         =  ($data['isOffline']) ? 0 : $data['order']->ORDR_TOTL - $data['order']->ORDR_PAID - $data['order']->ORDR_DISC;

        return view("orders.details", $data);
    }

    public function insertNewItems($orderID, Request $request)
    {
        $order = Order::findOrFail($orderID);
        DB::transaction(function () use ($order, $request) {
            $orderItemArray = $this->getOrderItemsArray($request);
            foreach ($orderItemArray as $item) {
                $orderItem = $order->order_items()->firstOrNew(
                    ['ORIT_FNSH_ID' => $item['ORIT_FNSH_ID'], "ORIT_SIZE" => $item['ORIT_SIZE'], "ORIT_PRCE" => $item['ORIT_PRCE']]
                );
                if ($orderItem->ORDR_VRFD == 1) {
                    $finished = Finished::findOrfail($item->ORIT_FNSH_ID);
                    $finished->incrementSizeQuantity($item->ORIT_SIZE, $item->ORIT_CUNT);
                }
                $orderItem->ORIT_CUNT += $item['ORIT_CUNT'];
                $orderItem->ORIT_SIZE = $item['ORIT_SIZE'];
                $orderItem->ORIT_VRFD = 0;
                $orderItem->save();
            }
            $oldTotal = $order->ORDR_TOTL;
            $order->recalculateTotal();
            Client::insertTrans($order->source->ORSC_CLNT_ID, $order->ORDR_TOTL - $oldTotal, 0, 0, 0, 0, "Automatically Added from Orders System", "New Items added on (" . $order->id . ")");
            $order->addTimeline("Items added on order - New Items Amount: " . ($order->ORDR_TOTL - $oldTotal));
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
                $order->addTimeline("Order set as Ready");
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
                $finished = Finished::findOrfail($item->ORIT_FNSH_ID);
                $isReturned = $finished->incrementSizeQuantity($item->ORIT_SIZE, $item->ORIT_CUNT);
                if (!$isReturned)
                    break;
            }
            if ($isReturned) {
                $order->ORDR_STTS_ID = 5;
                $order->ORDR_PAID = 0;
                $order->ORDR_DLVR_DATE = date('Y-m-d H:i:s');
                $order->ORDR_DRVR_ID = null;
                $order->save();
                $order->addTimeline("Order set as Cancelled");
                Client::insertTrans($order->source->ORSC_CLNT_ID, 0, 0, 0, 0,  $order->ORDR_TOTL, "Automatically Added from Orders System", "Order(" . $order->id . ") Cancelled");
            }
        });
        return redirect("orders/details/" . $order->id);
    }

    public function setNew($id)
    {

        $order = Order::findOrFail($id);
        DB::transaction(function () use ($order) {
            $order->ORDR_STTS_ID = 1;
            $order->save();
            $order->addTimeline("Order set as New");
        });
        return redirect("orders/details/" . $order->id);
    }

    public function setInDelivery($id)
    {
        $order = Order::findOrFail($id);
        DB::transaction(function () use ($order) {
            if ($order->ORDR_STTS_ID == 2 && isset($order->driver) && $order->driver->DRVR_ACTV) {
                $order->ORDR_STTS_ID = 3;
                $order->save();
                $order->addTimeline("Order set as In Delivery");
            }
        });
        return redirect("orders/details/" . $order->id);
    }

    public function setDelivered($id)
    {
        $order = Order::findOrFail($id);
        DB::transaction(function () use ($order) {
            $remainingMoney = $order->ORDR_TOTL - $order->ORDR_DISC - $order->ORDR_PAID;
            if ($order->ORDR_STTS_ID == 3 && $remainingMoney == 0) {
                $order->ORDR_STTS_ID = 4;
                $order->ORDR_DLVR_DATE = date('Y-m-d H:i:s');
                $order->save();
                $order->addTimeline("Order set as Delivered");
            }
        });
        return redirect("orders/details/" . $order->id);
    }

    public function setFullyReturned($id)
    {
        $order = Order::findOrFail($id);
        DB::transaction(function () use ($order) {
            $isReturned = true;
            foreach ($order->order_items as $item) {
                $finished = Finished::findOrfail($item->ORIT_FNSH_ID);
                $isSaved = true;
                if ($item->ORIT_VRFD)
                    $isSaved = $finished->incrementSizeQuantity($item->ORIT_CUNT, $item->ORIT_SIZE);
                if (!$isSaved) {
                    $isReturned = false;
                    break;
                }
            }
            if ($isReturned) {
                $order->ORDR_STTS_ID = 6;
                $order->ORDR_PAID = 0;
                $order->ORDR_DLVR_DATE = date('Y-m-d H:i:s');
                $order->save();
                $order->addTimeline("Order set as Returned");
                Client::insertTrans($order->source->ORSC_CLNT_ID, 0, 0, 0, 0,  $order->ORDR_TOTL, "Automatically Added from Orders System", "Order(" . $order->id . ") Returned");
            }
        });
        return redirect("orders/details/" . $order->id);
    }

    public function setPartiallyReturned($id)
    {
        //This function will create new return order 
        $order = Order::findOrFail($id);
        $retOrder = new Order();
        DB::transaction(function () use ($order, $retOrder) {
            if (isset($order->ORDR_USER_ID))
                $retOrder->ORDR_USER_ID = $order->ORDR_USER_ID;
            else {
                $retOrder->ORDR_GEST_NAME = $order->ORDR_GEST_NAME;
                $retOrder->ORDR_GEST_MOBN = $order->ORDR_GEST_MOBN;
            }
            $retOrder->ORDR_OPEN_DATE = date('Y-m-d H:i:s');
            $retOrder->ORDR_DLVR_DATE =  $retOrder->ORDR_OPEN_DATE;
            $retOrder->ORDR_ADRS = $order->ORDR_ADRS;
            $retOrder->ORDR_NOTE = "New Return Order for order number " . $order->id;
            $retOrder->ORDR_AREA_ID = $order->ORDR_AREA_ID;
            $retOrder->ORDR_ORSC_ID = $order->ORDR_ORSC_ID;
            $retOrder->ORDR_PYOP_ID = $order->ORDR_PYOP_ID;
            $retOrder->ORDR_STTS_ID = 6; // new returned order
            $retOrder->ORDR_TOTL = 0;
            $retOrder->save();
            $order->ORDR_RTRN_ID = $retOrder->id; // new returned order
            $order->save();
            $order->addTimeline("New Return Order linked");
        });
        return redirect("orders/details/" . $order->id);
    }

    public function assignDriver(Request $request)
    {
        $request->validate([
            'id' => "required",
            'driver' => "required|exists:drivers,id"
        ]);

        $order = Order::findOrFail($request->id);
        DB::transaction(function () use ($order, $request) {
            if ($order->ORDR_STTS_ID < 3) { // New or ready
                $order->ORDR_DRVR_ID = $request->driver;
                $order->save();
                $driver = Driver::findOrFail($request->driver);
                $order->addTimeline($driver->DRVR_NAME . " assigned as the order delivery man");
            }
        });
        return redirect("orders/details/" . $order->id);
    }

    public function collectNormalPayment(Request $request)
    {
        $order = Order::findOrFail($request->id);
        $request->validate([
            'id' => "required",
            'payment' => "required|min:0|max:" . $order->ORDR_TOTL
        ]);
        DB::transaction(function () use ($order, $request) {
            if ($order->ORDR_STTS_ID < 4) {
                $order->ORDR_PAID += $request->payment;
                $order->save();
                Client::insertTrans($order->source->ORSC_CLNT_ID, 0, $request->payment, 0, 0, 0, "Automatically Added from Orders System", "Order(" . $order->id . ") Payment");
                $order->addTimeline("Normal Payment ( " . $request->payment . "EGP ) Collected");
            }
        });
        return redirect("orders/details/" . $order->id);
    }

    public function collectDeliveryPayment(Request $request)
    {
        $order = Order::findOrFail($request->id);
        $request->validate([
            'id' => "required",
            'deliveryPaid' => "required|min:0"
        ]);

        DB::transaction(function () use ($order, $request) {
            $order->ORDR_DLFE = $request->deliveryPaid;
            $order->save();
            $order->addTimeline("Delivery Payment ( " . $request->deliveryPaid . "EGP ) Collected");
        });
        return redirect("orders/details/" . $order->id);
    }

    public function setDiscount(Request $request)
    {
        $order = Order::findOrFail($request->id);
        $request->validate([
            'id' => "required",
            'discount' => "required|min:0|max:" . $order->ORDR_TOTL
        ]);
        DB::transaction(function () use ($order, $request) {
            if ($order->ORDR_STTS_ID < 4) {
                $order->ORDR_DISC = $request->discount;
                $order->save();
                Client::insertTrans($order->source->ORSC_CLNT_ID, 0, 0, 0, $request->discount, 0, "Automatically Added from Orders System", "Order(" . $order->id . ") Discount");
                $order->addTimeline("Discount Set ( " . $request->discount . "EGP )");
            }
        });
        return redirect("orders/details/" . $order->id);
    }

    public function toggleItem($id)
    {

        $item = OrderItem::findOrfail($id);
        $finished = Finished::findOrFail($item->ORIT_FNSH_ID);
        $order = Order::findOrfail($item->ORIT_ORDR_ID);
        if ($order->ORDR_STTS_ID != 1) { //still new
            return 'failed';
        }
        try {
            DB::transaction(function () use ($finished, $item, $order) {
                if ($item->ORIT_VRFD) {
                    $finished->incrementSizeQuantity($item->ORIT_CUNT, $item->ORIT_SIZE);
                    $item->ORIT_VRFD = 0;
                    $item->save();
                    $order->addTimeline("Item set as Not Ready");
                } else {
                    $finished->incrementSizeQuantity(-1 * $item->ORIT_CUNT, $item->ORIT_SIZE);
                    $item->ORIT_VRFD = 1;
                    $item->save();
                    $order->addTimeline("Item set as Ready");
                }
            });
        } catch (Exception $e) {
            return 'failed';
        }
        return 1;
    }

    public function deleteItem($id)
    {

        $item = OrderItem::findOrfail($id);
        $order = Order::findOrfail($item->ORIT_ORDR_ID);
        DB::transaction(function () use ($order, $item) {
            if ($order->ORDR_STTS_ID != 1) { //still new
                return 'failed';
            }
            if ($item->ORIT_VRFD == 1) {
                $finished = Finished::findOrFail($item->ORIT_FNSH_ID);
                $finished->incrementSizeQuantity($item->ORIT_CUNT, $item->ORIT_SIZE);
            }
            $item->delete();
            $oldTotal = $order->ORDR_TOTL;
            $order->recalculateTotal();
            Client::insertTrans($order->source->ORSC_CLNT_ID, 0, 0, 0, 0, $oldTotal - $order->ORDR_TOTL, "Automatically Added from Orders System", "Item deleted on Order(" . $order->id . ")");
            $order->addTimeline("Items deleted, worth: " . ($oldTotal - $order->ORDR_TOTL) . "EGP");
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
            if (($order->ORDR_STTS_ID == 4 || $order->ORDR_STTS_ID == 3) && isset($order->ORDR_RTRN_ID) && is_numeric($order->ORDR_RTRN_ID)) {
                if ($request->count > $orderItem->ORIT_CUNT)
                    return redirect("orders/details/" . $orderItem->ORIT_ORDR_ID);
                //if and in delivery or delivered and has a returned order

                //create new return item and add count to return item
                $returnOrder = Order::findOrFail($order->ORDR_RTRN_ID);
                $returnedItem = $returnOrder->order_items()->firstOrNew([
                    'ORIT_FNSH_ID' => $orderItem->ORIT_FNSH_ID,
                    "ORIT_SIZE" => $orderItem->ORIT_SIZE
                ]);

                $returnedItem->ORIT_CUNT += $request->count;
                $returnedItem->ORIT_VRFD = 1;
                $returnedItem->save();
                $returnOrder->recalculateTotal();

                //Adjust finished
                $finished = Finished::findOrFail($orderItem->ORIT_FNSH_ID);
                if ($orderItem->ORIT_VRFD)
                    $finished->incrementSizeQuantity($request->count, $orderItem->ORIT_SIZE);

                //Adjust old order
                $orderItem->ORIT_CUNT -= $request->count;
                if ($orderItem->ORIT_CUNT < 1)
                    $orderItem->delete();
                else
                    $orderItem->save();
                $oldTotal = $order->ORDR_TOTL;
                $order->recalculateTotal();
                Client::insertTrans($order->source->ORSC_CLNT_ID, 0, 0, 0, 0, $oldTotal - $order->ORDR_TOTL, "Automatically Added from Orders System", "Item deleted on Order(" . $order->id . ")");
                $returnedTotal = $oldTotal - $order->ORDR_TOTL;
                $order->addTimeline("Items returned, worth: " . $returnedTotal . "EGP");
            } elseif ($order->ORDR_STTS_ID != 1) { //if it is not still new
                return redirect("orders/details/" . $orderItem->ORIT_ORDR_ID);
            } else {
                //returning order to inventory before changing count
                $finished = Finished::findOrFail($orderItem->ORIT_FNSH_ID);
                $finished->incrementSizeQuantity($orderItem->ORIT_CUNT, $orderItem->ORIT_SIZE);
                $orderItem->ORIT_CUNT = $request->count;
                $orderItem->ORIT_PRCE = $request->price;
                $orderItem->ORIT_VRFD = 0;
                $orderItem->save();
                $oldTotal = $order->ORDR_TOTL;
                $order->recalculateTotal();
                $orderDiff = $oldTotal - $order->ORDR_TOTL;
                if ($orderDiff > 0)
                    Client::insertTrans($order->source->ORSC_CLNT_ID, $orderDiff, 0, 0, 0, 0, "Automatically Added from Orders System", "Item changed on Order(" . $order->id . ")");
                elseif ($orderDiff < 0)
                    Client::insertTrans($order->source->ORSC_CLNT_ID, 0, 0, 0, 0, -1 * $orderDiff, "Automatically Added from Orders System", "Item changed on Order(" . $order->id . ")");
                $order->addTimeline("Items Quantity Changed, worth: " . $orderDiff . "EGP");
            }
        });
        return redirect("orders/details/" . $orderItem->ORIT_ORDR_ID);
    }

    public function editOrderInfo(Request $request)
    {
        $request->validate([
            "id" => "required",

        ]);
        $order = Order::findOrfail($request->id);
        $order->ORDR_ADRS = $request->address;
        $order->ORDR_NOTE = $request->note;
        $order->ORDR_AREA_ID = $request->area;
        $order->save();
        return redirect("orders/details/" . $order->id);
    }

    public function invoice($id)
    {
        $data = Order::getOrderDetails($id);
        $numberStr = number_format($data['order']->ORDR_TOTL, 2);
        $numArr = explode('.', $numberStr);
        $decimal = str_replace(",", "", $numArr[1]);
        $wholeNum = str_replace(",", "", $numArr[0]);
        $wholeConverter = new convert_ar($wholeNum, "male");
        $decimalConverter = new convert_ar($decimal, "male");

        $data['totalInArabic'] = $wholeConverter->convert_number() . " جنيها مصريا ";
        return view('orders.invoice', $data);
    }

    ////////////////////////////Insert Order from dashboard///////////////////////////

    public function insert($type, Request $request)
    {

        $request->validate([
            "user"          =>  "required_if:guest,2|nullable|exists:users,id",
            "guestName"     =>  "required_if:guest,1",
            "guestMob"      =>  "required_if:guest,1",
            "area"          =>  "required",
            "source"          =>  "required",
            "option"        =>  "required",
            "address"      =>  "required"
        ]);
        $order = new Order();
        try {
            DB::transaction(function () use ($order, $type, $request) {
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
                $order->ORDR_ORSC_ID = $request->source;
                $order->ORDR_PYOP_ID = $request->option;
                $order->ORDR_SRNO = Order::getNextSerialNumber($type);
                $order->ORDR_STTS_ID = 1; // new order
                $order->ORDR_DASH_ID = Auth::user()->id; // new order
                $order->ORDR_ONLN = $type;
                if ($type == 1) {
                    $orderItemArray = $this->getOrderItemsObjectArray($request);
                } else {
                    //offline items array
                    $orderItemArray = $this->getOfflineItemsArray($request);
                }
                $order->ORDR_TOTL = $this->getOrderTotal($orderItemArray);
                // foreach ($orderItemArray as $item) { removing items from inventory
                //     $finished = Finished::findOrFail($item->ORIT_FNSH_ID);
                //     $finished->FNSH_CUNT -= $item->ORIT_CUNT;
                //     $finished->save();
                // }
                $order->save();
                $order->order_items()->saveMany($orderItemArray);
                Client::insertTrans($order->source->ORSC_CLNT_ID, $order->ORDR_TOTL, 0, 0, 0, 0, $order->ORDR_NOTE, "New Order(" . $order->id . ")");
                $order->addTimeline("Order Created");
            });
        } catch (Exception $e) {
            throw $e;
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
    private function initTableArr($isActive, $state = -1, $month = -1, $year = -1, $type = 1)
    {
        if ($isActive == 1)
            $this->data['items']    = Order::getActiveOrders(-1, $type);
        elseif ($month == -1 && $year == -1) {
            $this->data['items']    = Order::getActiveOrders($state, $type);
        } else {
            $this->data['items']    = Order::getOrdersByDate(false, $month, $year, $state, $type);
        }
        $this->data['cardTitle'] = true;
        $this->data['cols'] = ['id', 'Client', 'Status', 'Area', 'Driver',  'Items', 'Ordered On', 'Closed On', 'Total'];
        $this->data['atts'] = [
            ['attUrl' => ['url' => "orders/details", "shownAtt" => 'id', "urlAtt" => 'id']],
            ['attOrAtt' => ['basicAtt' => "ORDR_GEST_NAME", "otherAtt" => 'ORSC_NAME']],
            [
                'stateQuery' => [
                    "classes" => [
                        "1" => "label-info",
                        "2" => "label-warning",
                        "3" =>  "label-dark bg-dark",
                        "4" =>  "label-success",
                        "5" =>  "label-danger",
                        "6" =>  "label-primary",
                    ],
                    "att"           =>  "ORDR_STTS_ID",
                    'foreignAtt'    => "STTS_NAME",
                    'url'           => "orders/details/",
                    'urlAtt'        =>  'id'
                ]
            ],
            'AREA_NAME',
            'DRVR_NAME',
            'itemsCount',
            ['dateStr' => ['att' => 'ORDR_OPEN_DATE', 'format' =>  'd-m-Y H:i']],
            ['dateStr' => ['att' => 'ORDR_DLVR_DATE', 'format' =>  'd-m-Y H:i']],
            'ORDR_TOTL'
        ];
        $this->data['type'] = $type;
    }

    private function getOrderItemsArray(Request $request)
    {
        $retArr = array();
        foreach ($request->item as $index => $item) {

            array_push(
                $retArr,
                ["ORIT_FNSH_ID" => $item, "ORIT_CUNT" => $request->count[$index], "ORIT_PRCE" => $request->price[$index], "ORIT_SIZE" => $request->size[$index]]
            );
        }
        return $retArr;
    }

    private function getOrderItemsObjectArray(Request $request)
    {
        $retArr = array();
        foreach ($request->item as $index => $item) {
            array_push($retArr, new OrderItem(
                ["ORIT_FNSH_ID" => $item, "ORIT_CUNT" => $request->count[$index], "ORIT_PRCE" => $request->price[$index], "ORIT_SIZE" => $request->size[$index]]
            ));
        }
        return $retArr;
    }


    private function getOfflineItemsArray($request)
    {

        $retArr = array();

        foreach ($request->amount36 as $key => $item) {

            if ($request->amount36[$key] >= 1) {
                array_push($retArr, new OrderItem(
                    ["ORIT_FNSH_ID" => $request->finished[$key], "ORIT_CUNT" => $request->amount36[$key], "ORIT_PRCE" => $request->price[$key], "ORIT_SIZE" => 36]
                ));
            }
            if ($request->amount38[$key] >= 1) {
                array_push($retArr, new OrderItem(
                    ["ORIT_FNSH_ID" => $request->finished[$key], "ORIT_CUNT" => $request->amount38[$key], "ORIT_PRCE" => $request->price[$key], "ORIT_SIZE" => 38]
                ));
            }
            if ($request->amount40[$key] >= 1) {
                array_push($retArr, new OrderItem(
                    ["ORIT_FNSH_ID" => $request->finished[$key], "ORIT_CUNT" => $request->amount40[$key], "ORIT_PRCE" => $request->price[$key], "ORIT_SIZE" => 40]
                ));
            }
            if ($request->amount42[$key] >= 1) {
                array_push($retArr, new OrderItem(
                    ["ORIT_FNSH_ID" => $request->finished[$key], "ORIT_CUNT" => $request->amount42[$key], "ORIT_PRCE" => $request->price[$key], "ORIT_SIZE" => 42]
                ));
            }
            if ($request->amount44[$key] >= 1) {
                array_push($retArr, new OrderItem(
                    ["ORIT_FNSH_ID" => $request->finished[$key], "ORIT_CUNT" => $request->amount44[$key], "ORIT_PRCE" => $request->price[$key], "ORIT_SIZE" => 44]
                ));
            }
            if ($request->amount46[$key] >= 1) {
                array_push($retArr, new OrderItem(
                    ["ORIT_FNSH_ID" => $request->finished[$key], "ORIT_CUNT" => $request->amount46[$key], "ORIT_PRCE" => $request->price[$key], "ORIT_SIZE" => 46]
                ));
            }
            if ($request->amount48[$key] >= 1) {
                array_push($retArr, new OrderItem(
                    ["ORIT_FNSH_ID" => $request->finished[$key], "ORIT_CUNT" => $request->amount48[$key], "ORIT_PRCE" => $request->price[$key], "ORIT_SIZE" => 48]
                ));
            }
            if ($request->amount50[$key] >= 1) {
                array_push($retArr, new OrderItem(
                    ["ORIT_FNSH_ID" => $request->finished[$key], "ORIT_CUNT" => $request->amount50[$key], "ORIT_PRCE" => $request->price[$key], "ORIT_SIZE" => 50]
                ));
            }

        }

        return $retArr;
    }

    private function getOrderTotal($items)
    {
        $total = 0;
        foreach ($items as $item) {
            //$price = Finished::findOrFail($item)->FNSH_PRCE;
            $total += $item->ORIT_CUNT * $item->ORIT_PRCE;
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
