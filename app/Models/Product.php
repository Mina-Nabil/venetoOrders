<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = "products";
    public $timestamps = true;

    protected $attributes = [
        "PROD_COST" => 0
    ];

    public function subcategory(){
        return $this->belongsTo("sub_categories", "PROD_SBCT_ID", 'id');
    }

    public function sizechart(){
        return $this->hasOne("size_chart", "PROD_SZCT_ID", "id");
    }

    public function mainImage(){
        return $this->hasOne("prod_images", "PROD_PIMG_ID", "id");
    }

    public function images(){
        return $this->hasMany("prod_images", "PIMG_PROD_ID", "id");
    }
    public function stock(){
        return $this->hasMany("inventory", "INVT_PROD_ID", "id");
    }

    public function tags(){
        return $this->belongsToMany("tags", "prod_tag", "PDTG_TAGS_ID", "PDTG_PROD_ID");
    }
}
