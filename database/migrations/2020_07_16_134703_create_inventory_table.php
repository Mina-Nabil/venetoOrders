<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

     

        Schema::create('sizes', function (Blueprint $table){
            $table->id();
            $table->string("SIZE_NAME")->unique();
            $table->string("SIZE_ARBC_NAME")->nullable();
            $table->string("SIZE_CODE")->unique();  //X - XL - S
        });

        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId("INVT_PROD_ID")->constrained("products");
            $table->foreignId("INVT_COLR_ID")->constrained("colors");
            $table->foreignId("INVT_SIZE_ID")->constrained("sizes");
            $table->integer("INVT_CUNT")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory');
        Schema::dropIfExists('sizes');
    }
}
