<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyModuleIbanProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_module_iban_providers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_module_id');
            $table->unsignedBigInteger('payment_provider_iban_id');
            $table->string('wallet');
            $table->string('api_key');
            $table->string('password');
            $table->boolean('is_active')->default(false);

            $table->unique(['company_module_id', 'payment_provider_iban_id']);
            $table->foreign('company_module_id')->references('id')->on('company_modules');
            $table->foreign('payment_provider_iban_id')->references('id')->on('payment_provider_ibans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_module_iban_providers');
    }
}
