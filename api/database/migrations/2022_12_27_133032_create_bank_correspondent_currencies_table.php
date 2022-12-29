<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankCorrespondentCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_correspondent_currencies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_correspondent_id');
            $table->unsignedBigInteger('currency_id');

            $table->foreign('bank_correspondent_id')->references('id')->on('bank_correspondents')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_correspondent_currencies');
    }
}
