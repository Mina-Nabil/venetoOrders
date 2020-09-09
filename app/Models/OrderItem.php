<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = "order_items";
    public $timestamps = false;

    public $fillable = [
        "ORIT_FNSH_ID", "ORIT_CUNT", "ORIT_SIZE" , "ORIT_PRCE" 
    ];
    public $attributes = [
        "ORIT_CUNT" => 0
    ];

    public function order()
    {
        return $this->belongsTo("App\Models\Order", "ORIT_ORDR_ID", "id");
    }

}
