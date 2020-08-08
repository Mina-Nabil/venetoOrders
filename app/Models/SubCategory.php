<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = "sub_categories";
    public $timestamps = false;
    
    public function category(){
        return $this->belongsTo("App\Models\Category", "SBCT_CATG_ID", "id");
    }
    public function icon(){
        return $this->belongsTo("App\Models\Icon", "SBCT_ICON_ID", "id");
    }
}
