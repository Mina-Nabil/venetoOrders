<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWishlistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wishlist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('WISH_PROD_ID')->constrained('products');
            $table->foreignId('WISH_USER_ID')->constrained('users');
            $table->tinyInteger('WISH_BGHT')->default(0);
            $table->timestamps();
        });
        Schema::create('cart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('CART_INVT_ID')->constrained('products');
            $table->foreignId('CART_USER_ID')->constrained('users');
            $table->tinyInteger('CART_CUNT')->default(0);
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
        Schema::dropIfExists('wishlist');
        Schema::dropIfExists('cart');
    }
}
