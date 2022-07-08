<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentSystemRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_system_regions', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_system_id');
            $table->unsignedBigInteger('region_id');
            $table->foreign('payment_system_id')->references('id')->on('payment_system')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_system_regions');
    }
}
