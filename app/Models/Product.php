<?php

namespace App\Models;

use DateInterval;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $table = "products";
    public $timestamps = true;

    protected $attributes = [
        "PROD_COST" => 0
    ];

    public function subcategory(){
        return $this->belongsTo("App\Models\SubCategory", "PROD_SBCT_ID", 'id');
    }

    public function sizechart(){
        return $this->hasOne("App\Models\SizeChart", "id", "PROD_SZCT_ID");
    }

    public function mainImage(){
        return $this->belongsTo("App\Models\ProductImages", "PROD_PIMG_ID", "id");
    }

    public function images(){
        return $this->hasMany("App\Models\ProductImages", "PIMG_PROD_ID", "id");
    }
    public function stock(){
        return $this->hasMany("App\Models\Inventory", "INVT_PROD_ID", "id");
    }

    public function sizes(){
        return DB::table("sizes")->join("inventory","INVT_SIZE_ID","=","sizes.id")
        ->join("products", "INVT_PROD_ID", "=", "inventory.id")
        ->selectRaw("DISTINCT SIZE_NAME")
        ->where("products.id","=", $this->id)
        ->get();
        
    }

    public static function newArrivals($dateInterval){
        return DB::table("products")
        ->join("inventory", "INVT_PROD_ID", "=", "products.id")
        ->select("products.*")->selectRaw("SUM(INVT_CUNT) as stock")
        ->groupBy("products.id")
        ->where("products.created_at" , ">", (new DateTime())->sub(new DateInterval($dateInterval)))
        ->get();
    }

    public function tags(){
        return $this->belongsToMany("App\Models\Tags", "prod_tag", "PDTG_PROD_ID", "PDTG_TAGS_ID");
    }
}
