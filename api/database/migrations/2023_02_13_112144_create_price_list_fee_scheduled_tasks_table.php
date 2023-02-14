<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceListFeeScheduledTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_list_fee_scheduled_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('price_list_fee_scheduled_id');
            $table->unsignedBigInteger('account_id');
            $table->unsignedInteger('currency_id');
            $table->date('date');

            $table->unique(['price_list_fee_scheduled_id', 'account_id', 'currency_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_list_fee_scheduled_tasks');
    }
}
