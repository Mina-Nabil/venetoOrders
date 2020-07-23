<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('colors', function (Blueprint $table){
            $table->id();
            $table->string('COLR_NAME')->unique();
            $table->string('COLR_ARBC_NAME')->unique();
            $table->string('COLR_CODE')->unique();
        });

        Schema::create('tags', function(Blueprint $table){
            $table->id();
            $table->string("TAGS_NAME")->unique();
            $table->string("TAGS_ARBC_NAME");
            $table->string("TAGS_SNDX");
            $table->string("TAGS_ARBC_SNDX");
        });

        Schema::create('prod_images', function (Blueprint $table){
            $table->id();
            $table->string("PIMG_URL");
            $table->foreignId("PIMG_PROD_ID");
            $table->foreignId("PIMG_COLR_ID")->nullable()->constrained("colors");
        });

        Schema::create('size_chart', function (Blueprint $table){
            $table->id();
            $table->string("SZCT_URL");
            $table->string("SZCT_NAME");
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("PROD_NAME")->unique();
            $table->string("PROD_ARBC_NAME");
            $table->foreignId("PROD_SBCT_ID")->constrained("sub_categories");
            $table->string("PROD_DESC");
            $table->string("PROD_ARBC_DESC");
            $table->double("PROD_PRCE");
            $table->double("PROD_COST")->nullable();
            $table->double("PROD_OFFR")->default(0); //percentage
            $table->foreignId("PROD_PIMG_ID")->nullable()->constrained("prod_images"); // main image
            $table->foreignId("PROD_SZCT_ID")->nullable()->constrained("size_chart"); // size chart image showing the size details
            $table->string("PROD_BRCD")->nullable();
            $table->timestamps();
        });

        Schema::table("prod_images", function (Blueprint $table){
            $table->foreign("PIMG_PROD_ID")->references("id")->on("products");
        });

        Schema::create("prod_tag", function(Blueprint $table){
            $table->id();
            $table->foreignId("PDTG_PROD_ID")->constrained("products");
            $table->foreignId("PDTG_TAGS_ID")->constrained("tags");
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prod_tag');
        Schema::table('prod_images', function (Blueprint $table){
            $table->dropForeign("prod_images_pimg_prod_id_foreign");
        });
        Schema::dropIfExists('products');
        Schema::dropIfExists('size_chart');
        Schema::dropIfExists('prod_images');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('colors');

    }
}
