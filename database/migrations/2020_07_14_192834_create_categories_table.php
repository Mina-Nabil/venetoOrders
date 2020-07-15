<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string("CATG_NAME");
            $table->string("CATG_ARBC_NAME");


        });

        Schema::create('sub_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId("SBCT_CATG_ID")->constrained("categories");
            $table->string("SBCT_NAME");
            $table->string("SBCT_ARBC_NAME");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_categories');
        Schema::dropIfExists('categories');
    }
}
