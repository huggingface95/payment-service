<?php

use App\Enums\PaymentProviderTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsActivePaymentProviderIdPaymentProviderTypeColumnsToProjectApiSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_api_settings', function (Blueprint $table) {
            $table->boolean('is_active')->default(false);
            $table->unsignedBigInteger('payment_provider_id')->nullable();
            $table->enum('payment_provider_type', [PaymentProviderTypeEnum::PAYMENT->toString(), PaymentProviderTypeEnum::IBAN->toString()])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_api_settings', function (Blueprint $table) {
            $table->dropColumn('is_active','payment_provider_id','payment_provider_type');
        });
    }
}
