<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentSystemOperationTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_system_operation_types', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_system_id');
            $table->unsignedBigInteger('operation_type_id');
            $table->foreign('payment_system_id')->references('id')->on('payment_system')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('operation_type_id')->references('id')->on('operation_type')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_system_operation_types');
    }
}
