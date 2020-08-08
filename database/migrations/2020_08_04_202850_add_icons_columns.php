<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIconsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('icons', function(Blueprint $table){
            $table->id();
            $table->string("ICON_NAME");
            $table->string("ICON_PATH");
        });

        Schema::table('sub_categories', function (Blueprint $table){
            $table->foreignId("SBCT_ICON_ID")->nullable()->constrained("icons");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("sub_categories", function (Blueprint $table){
            $table->dropForeign("subcategories_sbct_icon_id_foreign");
            $table->dropColumn("SBCT_ICON_ID");
        });
        Schema::dropIfExists('icons');
    }
}
