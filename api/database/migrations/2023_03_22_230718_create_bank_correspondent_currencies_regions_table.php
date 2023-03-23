<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankCorrespondentCurrenciesRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_correspondent_currencies_regions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_correspondent_id');
            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('region_id');

            $table->unique(['bank_correspondent_id', 'currency_id', 'region_id']);

            $table->foreign('bank_correspondent_id')->references('id')->on('bank_correspondents')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('region_id')->references('id')->on('regions')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_correspondent_currencies_regions');
    }
}
