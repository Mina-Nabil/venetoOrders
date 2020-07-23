<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $table = "orders";
    public $timestamps = true;

    public function order_items(){
        return $this->hasMany("App\Models\OrderItem", "ORIT_ORDR_ID", "id");
    }

    public function client(){
        return $this->belongsTo("App\Models\User", "ORDR_USER_ID", "id");
    }

    public function area(){
        return $this->belongsTo("App\Models\Area", "ORDR_AREA_ID", "id");
    }

    public function paymentOption(){
        return $this->belongsTo("payment_options", "ORDR_AREA_ID", "id");
    }

    public function getActiveOrders(){
        return DB::table("orders")
        ->join("order_status", "ORDR_STTS_ID", "=", "order_status.id")
        ->join("areas", "ORDR_AREA_ID", "=", "areas.id")
        ->join("users", "ORDR_USER_ID", "=", "id")
        ->join("payment_options", "ORDR_PYOP_ID", "=", "payment_options.id")
        ->select("orders.*", "areas.AREA_NAME", "users.USER_NAME", "users.USER_MOBN", "payment_options.PYOP_NAME")
        ->where("ORDR_STTS_ID", "=", 1)->orWhere("ORDR_STTS_ID", "=", 1)->orWhere("ORDR_STTS_ID", "=", 2)->orWhere("ORDR_STTS_ID", "=", 3)
        ->get();
    }

}
