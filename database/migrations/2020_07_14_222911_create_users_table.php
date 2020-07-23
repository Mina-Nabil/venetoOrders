<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table){
            $table->id();
            $table->string("AREA_NAME")->unique();
            $table->string("AREA_ARBC_NAME");
            $table->double("AREA_RATE")->default(20);
            $table->tinyInteger("AREA_ACTV")->default(1);
        });

        Schema::create('genders', function (Blueprint $table){
            $table->id();
            $table->string("GNDR_NAME")->unique();
            $table->string("GNDR_ARBC_NAME");
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string("USER_NAME");
            $table->string("USER_MAIL")->unique();
            $table->string("USER_ADRS")->nullable();
            $table->foreignId("USER_AREA_ID")->nullable()->constrained("areas");
            $table->foreignId("USER_GNDR_ID")->constrained("genders")->default(1);
            $table->string("USER_MOBN");
            $table->tinyInteger("USER_MOBN_VRFD")->default(0);
            $table->tinyInteger("USER_MAIL_VRFD")->default(0);
            $table->string("USER_PASS")->nullable();
            $table->string("USER_FBTK")->nullable();
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
        Schema::dropIfExists('users');
        Schema::dropIfExists('genders');
        Schema::dropIfExists('areas');
    }
}
