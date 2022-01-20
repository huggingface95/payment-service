<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 8, 2);
            $table->decimal('fee', 8, 2);
            $table->string('currency', 255);
            $table->tinyInteger('status ');
            $table->string('sender_name', 255);
            $table->text('payment_details');
            $table->string('sender_bank_account', 255);
            $table->string('sender_swift', 255);
            $table->string('sender_bank_name', 255);
            $table->unsignedBigInteger('sender_bank_country');
            $table->string('sender_bank_address', 255);
            $table->unsignedBigInteger('sender_country');
            $table->string('sender_address', 255);
            $table->unsignedBigInteger('urgency_id');
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('payment_provider_id');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('company_id');
            $table->string('payment_number', 255);
            $table->timestamps();
            $table->foreign('urgency_id')->references('id')->on('payment_urgency');
            $table->foreign('type_id')->references('id')->on('payment_types');
            $table->foreign('payment_provider_id')->references('id')->on('payment_provider');
            $table->foreign('sender_bank_country')->references('id')->on('countries');
            $table->foreign('sender_country')->references('id')->on('countries');
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
