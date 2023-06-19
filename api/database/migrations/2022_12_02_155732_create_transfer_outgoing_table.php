<?php

use App\Enums\PaymentUrgencyEnum;
use App\Enums\TransferChannelEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferOutgoingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_outgoings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requested_by_id');
            $table->enum('user_type', [class_basename(ApplicantIndividual::class), class_basename(Members::class)]);
            $table->decimal('amount', 15, 5);
            $table->decimal('amount_debt', 15, 5);
            $table->unsignedBigInteger('currency_id');
            $table->unsignedInteger('status_id');
            $table->unsignedBigInteger('urgency_id')->default(PaymentUrgencyEnum::STANDART->value);
            $table->unsignedInteger('operation_type_id');
            $table->unsignedBigInteger('payment_provider_id');
            $table->unsignedBigInteger('payment_system_id');
            $table->unsignedBigInteger('payment_bank_id');
            $table->string('payment_number', 255);
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('sender_id');
            $table->enum('sender_type', [class_basename(ApplicantIndividual::class), class_basename(ApplicantCompany::class)]);
            $table->unsignedBigInteger('company_id');
            $table->string('system_message', 255);
            $table->string('reason', 255);
            $table->enum('channel', [
                TransferChannelEnum::CLIENT_DASHBOARD->toString(),
                TransferChannelEnum::BACK_OFFICE->toString(),
                TransferChannelEnum::CLIENT_MOBILE_APPLICATION->toString(),
            ]);
            $table->string('bank_message', 255);
            $table->string('recipient_account', 255);
            $table->string('recipient_bank_name', 255);
            $table->string('recipient_bank_address', 255);
            $table->string('recipient_bank_swift', 255)->nullable();
            $table->unsignedInteger('recipient_bank_country_id');
            $table->string('recipient_name', 255);
            $table->unsignedInteger('recipient_country_id');
            $table->string('recipient_city', 255);
            $table->string('recipient_address', 255);
            $table->string('recipient_state', 255);
            $table->string('recipient_zip', 255);
            $table->unsignedBigInteger('respondent_fees_id');
            $table->timestamp('execution_at');
            $table->timestamps();

            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->foreign('status_id')->references('id')->on('payment_status');
            $table->foreign('urgency_id')->references('id')->on('payment_urgency');
            $table->foreign('operation_type_id')->references('id')->on('operation_type');
            $table->foreign('payment_provider_id')->references('id')->on('payment_provider');
            $table->foreign('payment_system_id')->references('id')->on('payment_system');
            $table->foreign('payment_bank_id')->references('id')->on('payment_banks');
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('recipient_bank_country_id')->references('id')->on('countries');
            $table->foreign('recipient_country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfer_outgoings');
    }
}
