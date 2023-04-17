<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeRegionIdNullableInCommissionPriceListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commission_price_list', function (Blueprint $table) {
            $table->unsignedBigInteger('region_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commission_price_list', function (Blueprint $table) {
            $table->unsignedBigInteger('region_id')->nullable(false)->change();
        });
    }
}
