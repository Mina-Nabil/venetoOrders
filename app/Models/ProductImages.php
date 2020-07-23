<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
    protected $table = "prod_images";
    public $timestamps = false;

    public function color(){
        return $this->belongsTo("App\Models\Color", "PIMG_COLR_ID", 'id');
    }

}
