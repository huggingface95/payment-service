<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentProviderCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_provider_companies', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_provider_id');
            $table->unsignedBigInteger('company_id');
            $table->unique(['payment_provider_id', 'company_id']);
            $table->foreign('payment_provider_id')->references('id')->on('payment_provider')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_provider_companies');
    }
}
