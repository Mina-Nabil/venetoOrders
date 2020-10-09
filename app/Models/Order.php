<?php

namespace App\Models;


use DateInterval;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $table = "orders";
    public $timestamps = true;

    public function order_items()
    {
        return $this->hasMany("App\Models\OrderItem", "ORIT_ORDR_ID", "id");
    }

    public function timeline()
    {
        return $this->hasMany("timeline", "ORIT_ORDR_ID", "id");
    }

    public function area()
    {
        return $this->belongsTo("App\Models\Area", "ORDR_AREA_ID", "id");
    }

    public function status()
    {
        return $this->belongsTo("order_status", "ORDR_STTS_ID", "id");
    }

    public function driver()
    {
        return $this->belongsTo("App\Models\Driver", "ORDR_DRVR_ID", "id");
    }

    public function paymentOption()
    {
        return $this->belongsTo("payment_options", "ORDR_AREA_ID", "id");
    }

    public function source()
    {
        return $this->belongsTo("App\Models\OrderSource", "ORDR_ORSC_ID", "id");
    }

    public function recalculateTotal()
    {
        $total = 0;
        foreach ($this->order_items as $item) {
            //$price = Finished::findOrFail($item->ORIT_FNSH_ID)->FNSH_PRCE;
            $total += $item->ORIT_CUNT * $item->ORIT_PRCE;
        }
        $this->ORDR_TOTL = $total;
        $this->save();
    }

    public static function getOrdersByDate(bool $currentMonth = true, int $month = -1, int $year = -1, int $state = -1, int $type = 1)
    {

        $startDate = '';
        $endDate = '';

        if ($currentMonth) {
            $startDate = (new DateTime("first day of this month"))->format('Y-m-d 0:0:0');
            $endDate = (date_add((new DateTime('now')), new DateInterval("P01M")))->format('Y-m-1 0:0:0');
        } elseif ($month != -1 && $year == -1) {
            assert((0 < $month) && ($month < 13), 'Invalid Month');
            $year = date('Y');
            $startDate = $year . '-' . $month . '-01';
            $endDate   = date_add((new DateTime($startDate)), new DateInterval("P01M"))->format('Y-m-1 0:0:0');
        } elseif ($month == -1 && $year != -1) {
            $startDate = $year . '-01-01 00:00:00';
            $endDate = $year . '-12-31 12:59:59';
        } else {
            assert((0 < $month) && ($month < 13), 'Invalid Month');
            $startDate = $year . '-' . $month . '-01';
            $endDate   = date_add((new DateTime($startDate)), new DateInterval("P01M"))->format('Y-m-1 0:0:0');
        }


        $query =  self::tableQuery();

        if ($state > 0 && $state < 7) {
            $query = $query->where("ORDR_STTS_ID", "=", $state);
        } else {
            $query = $query->where("ORDR_STTS_ID", ">", 3);
        }
        $query = $query->where("ORDR_ONLN", $type);
        $query = $query->whereBetween("ORDR_DLVR_DATE", [$startDate, $endDate]);

        return $query->get();
    }

    public static function getActiveOrders($state = -1, $type = 1)
    {
        $query = self::tableQuery();
        if ($state > 0 && $state < 6) {
            $query = $query->where("ORDR_STTS_ID", "=", $state);
        } else {
            $query = $query->where([
                ["ORDR_STTS_ID", "=", 1, 'or'],
                ["ORDR_STTS_ID", "=", 2, 'or'],
                ["ORDR_STTS_ID", "=", 3, 'or'],
            ]);
        }
        $query = $query->where("ORDR_ONLN", $type);

        return $query->get();
    }

    public static function getOrderDetails($id)
    {
        $ret['order'] = DB::table("orders")
            ->join("order_status", "ORDR_STTS_ID", "=", "order_status.id")
            ->join("areas", "ORDR_AREA_ID", "=", "areas.id")
            ->join("order_sources", "ORDR_ORSC_ID", "=", "order_sources.id")
            ->join("clients", "ORSC_CLNT_ID", "=", "clients.id")
            ->Leftjoin("dash_users", "ORDR_DASH_ID", "=", "dash_users.id")
            ->Leftjoin("drivers", "ORDR_DRVR_ID", "=", "drivers.id")
            ->Leftjoin("order_items", "ORIT_ORDR_ID", "=", "orders.id")
            ->join("payment_options", "ORDR_PYOP_ID", "=", "payment_options.id")
            ->select("orders.*", 'drivers.DRVR_NAME', "orders.ORDR_GEST_NAME", "order_status.STTS_NAME", "order_sources.ORSC_NAME", "areas.AREA_NAME", "AREA_RATE", "dash_users.DASH_USNM", "payment_options.PYOP_NAME", "clients.CLNT_NAME", "clients.CLNT_SRNO")->selectRaw("SUM(ORIT_CUNT) as itemsCount")
            ->groupBy("orders.id", "order_status.STTS_NAME", "areas.AREA_NAME", "payment_options.PYOP_NAME")
            ->where('orders.id', $id)->get()->first();

        $ret['items'] = DB::table('order_items')->join("finished", "ORIT_FNSH_ID", "=", "finished.id")
            ->join("brands", "FNSH_BRND_ID", "=", "brands.id")
            ->join("models", "FNSH_MODL_ID", "=", "models.id")
            ->select("order_items.*", "BRND_NAME", "MODL_NAME", "MODL_UNID", "ORIT_CUNT", "ORIT_VRFD", "ORIT_PRCE")
            ->where("ORIT_ORDR_ID", "=", $id)
            ->get();


        $ret['timeline'] = DB::table('timeline')
            ->join('dash_users', 'TMLN_DASH_ID', '=', 'dash_users.id')
            ->select('DASH_USNM', 'timeline.*')
            ->orderByDesc('timeline.id')
            ->where('TMLN_ORDR_ID', $id)->get();

        return $ret;
    }

    public static function getOrdersCountByState($state, $startDate = null, $endDate = null, $type = 1)
    {
        $query = DB::table("orders")->where("ORDR_STTS_ID", $state);

        if (!is_null($startDate) && !is_null($endDate)) {
            $query = $query->whereBetween('ORDR_DLVR_DATE', [$startDate, $endDate]);
        }
        $query = $query->where("ORDR_ONLN", $type);
        return $query->get()->count();
    }

    public static function getNextSerialNumber($type)
    {
        $latestID = self::latest('id')->where('ORDR_ONLN', $type)->first()->ORDR_SRNO ?? 0;
        $yearBaseCode = date('y') . "0000";
       
        if ($yearBaseCode > $latestID)
            return $yearBaseCode + 1;
        else
            return $latestID + 1;
    }

    public static function getSalesIncome($month, $year, $catg = -1, $subCatg = -1)
    {
    }

    public static function getModelsIncome($month, $year, $product)
    {
    }

    private static function tableQuery()
    {
        return DB::table("orders")
            ->join("order_status", "ORDR_STTS_ID", "=", "order_status.id")
            ->join("areas", "ORDR_AREA_ID", "=", "areas.id")
            ->join("order_sources", "ORDR_ORSC_ID", "=", "order_sources.id")
            ->Leftjoin("dash_users", "ORDR_DASH_ID", "=", "dash_users.id")
            ->Leftjoin("order_items", "ORIT_ORDR_ID", "=", "orders.id")
            ->join("payment_options", "ORDR_PYOP_ID", "=", "payment_options.id")
            ->select("orders.*", "order_status.STTS_NAME", "dash_users.DASH_USNM", "areas.AREA_NAME", "payment_options.PYOP_NAME", "ORSC_NAME")->selectRaw("SUM(ORIT_CUNT) as itemsCount")
            ->groupBy("orders.id", "orders.ORDR_STTS_ID", "orders.ORDR_OPEN_DATE", "order_status.STTS_NAME", "areas.AREA_NAME", "payment_options.PYOP_NAME");
    }

    public function addTimeline($text, $isdash = true)
    {
        $timeline = new Timeline();
        $timeline->TMLN_DASH_ID = ($isdash) ? Auth::user()->id : "NULL";
        $timeline->TMLN_ORDR_ID = $this->id;
        $timeline->TMLN_TEXT    = $text;
        $timeline->save();
    }
}
