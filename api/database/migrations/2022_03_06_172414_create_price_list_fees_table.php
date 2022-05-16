<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceListFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_list_fees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('price_list_id');
            $table->tinyInteger('type_id')->default(0);
            $table->tinyInteger('operation_type_id')->default(0);
            $table->integer('period_id')->default(0);
            $table->json('fee')->nullable();
            $table->timestamps();

            $table->foreign('price_list_id')->references('id')->on('commission_price_list');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_list_fees');
    }
}
