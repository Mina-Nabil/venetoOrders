<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table="categories";
    public $timestamps = false;
    
    public function subCategory(){
        return $this->hasMany("App\Models\SubCategory", "SBCT_CATG_ID", "id");
    }
}
