<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequisitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisites', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('account_id');
            $table->string('recipient', 255);
            $table->bigInteger('registration_number');
            $table->string('address', 255);
            $table->integer('country_id');
            $table->string('bank_name', 255);
            $table->string('bank_address', 255);
            $table->integer('bank_country_id');
            $table->string('iban', 255);
            $table->string('account_no', 255);
            $table->string('swift', 255);
            $table->json('bank_correspondent');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->foreign('bank_country_id')->references('id')->on('countries');
            $table->foreign('account_id')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requisites');
    }
}
