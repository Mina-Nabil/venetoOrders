<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrdersTableLast extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table){
            $table->tinyInteger('ORDR_ONLN')->default(1);
            $table->unsignedInteger('ORDR_SRNO')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table){
            $table->dropColumn('ORDR_ONLN');
            $table->dropColumn('ORDR_SRNO');
        });
    }
}
