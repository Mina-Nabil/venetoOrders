<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('order_sources', function (Blueprint $table){
            $table->id();
            $table->string("ORSC_NAME")->unique();
            $table->unsignedBigInteger("ORSC_CLNT_ID");
            $table->foreign("ORSC_CLNT_ID")->references("id")->on("clients");
        });

        Schema::create('areas', function (Blueprint $table){
            $table->id();
            $table->string("AREA_NAME")->unique();
            $table->string("AREA_ARBC_NAME");
            $table->double("AREA_RATE")->default(20);
            $table->tinyInteger("AREA_ACTV")->default(1);
        });
        
        Schema::create('payment_options', function (Blueprint $table) {
            $table->id();
            $table->string("PYOP_NAME")->unique();
            $table->string("PYOP_ARBC_NAME");
            $table->tinyInteger("PYOP_ACTV")->default(1);
        });

        Schema::create('order_status', function (Blueprint $table) {
            $table->id();
            $table->string("STTS_NAME")->unique();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->dateTime("ORDR_OPEN_DATE");
            $table->dateTime("ORDR_DLVR_DATE")->nullable();
            $table->foreignId("ORDR_STTS_ID")->constrained("order_status");
            $table->string("ORDR_GEST_NAME")->nullable();
            $table->string("ORDR_GEST_MOBN")->nullable();
            $table->string("ORDR_ADRS");
            $table->foreignId("ORDR_AREA_ID")->constrained("areas");
            $table->foreignId("ORDR_ORSC_ID")->constrained("order_sources");
            $table->foreignId("ORDR_PYOP_ID")->constrained("payment_options");
            $table->double("ORDR_TOTL");
            $table->string("ORDR_NOTE")->nullable();
            $table->double("ORDR_PAID")->default(0);
            $table->unsignedInteger("ORDR_DASH_CLCT")->nullable();
            $table->foreign("ORDR_DASH_CLCT")->references("id")->on("dash_users");
            $table->timestamps();
        });

        Schema::create('order_items', function(Blueprint $table){
            $table->id();
            $table->foreignId("ORIT_ORDR_ID")->constrained("orders");
            $table->unsignedBigInteger("ORIT_FNSH_ID");
            $table->foreign("ORIT_FNSH_ID")->references("id")->on("finished");
            $table->unsignedInteger("ORIT_SIZE");
            $table->tinyInteger("ORIT_CUNT")->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_status');
        Schema::dropIfExists('payment_options');
        Schema::dropIfExists('areas');
        Schema::dropIfExists('order_sources');
    }
}
