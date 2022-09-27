<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecipientAndBeneficiaryFieldsToPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('price_list_fees_id');
            $table->string('recipient_account', 255);
            $table->string('recipient_bank_name', 255);
            $table->string('recipient_bank_address', 255);
            $table->string('recipient_bank_swift', 255);
            $table->unsignedBigInteger('recipient_bank_country_id');
            $table->string('beneficiary_name', 255);
            $table->string('beneficiary_state', 255);
            $table->unsignedBigInteger('beneficiary_country_id');
            $table->string('beneficiary_address', 255);
            $table->string('beneficiary_city', 255);
            $table->string('beneficiary_zip', 255);
            $table->jsonb('beneficiary_additional_data', 255)->nullable();
            $table->unsignedBigInteger('respondent_fees_id');
            $table->timestamp('execution_at')->nullable();

            $table->foreign('price_list_fees_id')->references('id')->on('price_list_fees');
            $table->foreign('recipient_bank_country_id')->references('id')->on('countries');
            $table->foreign('beneficiary_country_id')->references('id')->on('countries');
            $table->foreign('respondent_fees_id')->references('id')->on('respondent_fees');

            $table->dropForeign('payments_sender_bank_country_foreign');
            $table->dropForeign(['sender_country_id']);

            $table->dropColumn('sender_name');
            $table->dropColumn('sender_bank_account');
            $table->dropColumn('sender_swift');
            $table->dropColumn('sender_bank_name');
            $table->dropColumn('sender_bank_country');
            $table->dropColumn('sender_bank_address');
            $table->dropColumn('sender_country_id');
            $table->dropColumn('sender_address');
            $table->dropColumn('sender_additional_fields');
            $table->dropColumn('sender_email');
            $table->dropColumn('sender_phone');
            $table->dropColumn('payment_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign([
                'price_list_fees_id', 'recipient_bank_country_id', 'beneficiary_country_id', 'respondent_fees_id',
            ]);

            $table->dropColumn('price_list_fees_id');
            $table->dropColumn('recipient_account');
            $table->dropColumn('recipient_bank_name');
            $table->dropColumn('recipient_bank_address');
            $table->dropColumn('recipient_bank_swift');
            $table->dropColumn('recipient_bank_country');
            $table->dropColumn('beneficiary_name');
            $table->dropColumn('beneficiary_state');
            $table->dropColumn('beneficiary_country');
            $table->dropColumn('beneficiary_address');
            $table->dropColumn('beneficiary_city');
            $table->dropColumn('beneficiary_zip');
            $table->dropColumn('beneficiary_additional_data');
            $table->dropColumn('respondent_fees_id');
            $table->dropColumn('execution_at');

            $table->string('sender_name', 255);
            $table->string('sender_bank_account', 255);
            $table->string('sender_swift', 255);
            $table->string('sender_bank_name', 255);
            $table->unsignedBigInteger('sender_bank_country');
            $table->string('sender_bank_address', 255);
            $table->unsignedBigInteger('sender_country_id');
            $table->string('sender_address', 255);
            $table->jsonb('sender_additional_fields')->nullable();
            $table->string('sender_email')->nullable();
            $table->string('sender_phone')->nullable();
            $table->text('payment_details');

            $table->foreign('sender_bank_country')->references('id')->on('countries');
            $table->foreign('sender_country_id')->references('id')->on('countries');
        });
    }
}
