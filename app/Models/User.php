<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    protected $table="users";
    public $timestamps = true;

    protected $attributes = array(
        "USER_MOBN_VRFD" => 0,
        "USER_MAIL_VRFD" => 0,
    );
    
    public function area(){
        return $this->belongsTo("App\Models\Area", "USER_AREA_ID", 'id');
    }
    
    public function gender(){
        return $this->belongsTo("App\Models\Gender", "USER_GNDR_ID", 'id');
    }

    public function wishlist(){
        return $this->belongsToMany("App\Models\Product","wishlist", "WISH_PROD_ID", "WISH_USER_ID");
    }

    public function orders(){
        return $this->hasMany("App\Models\Order", "ORDR_USER_ID", "id");
    }

    public function cart(){
        return $this->belongsToMany("App\Models\Inventory","cart", "CART_INVT_ID", "CART_USER_ID");
    }

    public function itemsBought(){
        return DB::table('order_items')
            ->join('inventory' , 'ORIT_INVT_ID', '=', 'inventory.id')
            ->join('products', 'INVT_PROD_ID', '=', 'products.id')
            ->join('sizes', 'INVT_SIZE_ID', '=', 'sizes.id')
            ->join('colors', 'INVT_COLR_ID', '=', 'colors.id')
            ->join('orders', 'ORIT_ORDR_ID', '=', 'orders.id')
            ->selectRaw("PROD_NAME, SUM(ORIT_CUNT) as itemCount, COLR_NAME, SIZE_NAME")
            ->groupBy('order_items.id')
            ->where('ORDR_USER_ID', $this->id)
            ->where('ORDR_STTS_ID' , 4)->get();
    }

    public function moneyPaid(){
        return DB::table('orders')->where('ORDR_USER_ID', $this->id)->where('ORDR_STTS_ID', 4)
                ->selectRaw('SUM(ORDR_PAID) as paid, SUM(ORDR_DISC) as discount')
                ->get()->first();
    }
}


