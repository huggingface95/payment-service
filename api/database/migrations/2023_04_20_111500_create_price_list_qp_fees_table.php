<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceListQpFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_list_qp_fees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->tinyInteger('type_id');
            $table->integer('period_id');
            $table->integer('quote_provider_id');
            $table->timestamps();

            $table->foreign('quote_provider_id')->references('id')->on('quote_providers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_list_qp_fees');
    }
}
