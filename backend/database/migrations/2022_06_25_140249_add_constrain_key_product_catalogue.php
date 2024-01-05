<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConstrainKeyProductCatalogue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('san_phams', function (Blueprint $table) {
        $table->foreign('product_catalogues_id')->references('id')->on('product_catalogues')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('san_phams', function (Blueprint $table) {
            //
            $table->dropForeign('san_phams_product_catalogues_id_foreign');
        });
    }
}
