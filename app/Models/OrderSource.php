<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderSource extends Model
{
    protected $table = "order_sources";
    public $timestamps = false;


    public function orders(){
        return $this->hasMany('App\Models\Order', "ORDR_ORSC_ID", 'id');
    }

    public function client_account(){
        return $this->belongsTo('App\Models\Client', 'ORSC_CLNT_ID');
    }
}
