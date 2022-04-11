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
            $table->decimal('amount', 15, 5);
            $table->decimal('amount_real', 15, 5);
            $table->decimal('fee', 15, 5);
            $table->unsignedBigInteger('fee_type_id');
            $table->unsignedBigInteger('currency_id');
            $table->tinyInteger('status_id');
            $table->string('sender_name', 255);
            $table->text('payment_details');
            $table->string('sender_bank_account', 255);
            $table->string('sender_swift', 255);
            $table->string('sender_bank_name', 255);
            $table->unsignedBigInteger('sender_bank_country');
            $table->string('sender_bank_address', 255);
            $table->unsignedBigInteger('sender_country_id');
            $table->string('sender_address', 255);
            $table->unsignedBigInteger('urgency_id');
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('payment_provider_id');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('member_id');
            $table->string('payment_number', 255);
            $table->string('error', 255);
            $table->timestamp('received_at');
            $table->jsonb('sender_additional_fields')->nullable();
            $table->timestamps();
            $table->foreign('urgency_id')->references('id')->on('payment_urgency');
            $table->foreign('type_id')->references('id')->on('payment_types');
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('payment_provider_id')->references('id')->on('payment_provider');
            $table->foreign('sender_bank_country')->references('id')->on('countries');
            $table->foreign('sender_country_id')->references('id')->on('countries');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->foreign('fee_type_id')->references('id')->on('fee_types');
            $table->foreign('status_id')->references('id')->on('payment_status');
            $table->foreign('member_id')->references('id')->on('members');
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
