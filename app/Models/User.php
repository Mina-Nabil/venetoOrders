<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

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
        return $this->hasMany("App\Models\Product", "ORDR_USER_ID", "id");
    }

    public function cart(){
        return $this->belongsToMany("App\Models\Product","wishlist", "WISH_PROD_ID", "WISH_USER_ID");
    }
}


