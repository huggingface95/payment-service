<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePriceListModeFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_list_mode_fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fees_mode_id');
            $table->unsignedBigInteger('price_list_fees_id');
            $table->decimal('fee',15,5)->default(0);
            $table->decimal('fee_from',15,5)->default(0);
            $table->decimal('fee_to',15,5)->default(0);
            $table->foreign('fees_mode_id')->references('id')->on('fees_mode');
            $table->foreign('price_list_fees_id')->references('id')->on('price_list_fees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_list_mode_fees');
    }
}
