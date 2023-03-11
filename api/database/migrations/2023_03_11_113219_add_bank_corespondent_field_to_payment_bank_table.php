<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankCorespondentFieldToPaymentBankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_banks', function (Blueprint $table) {
            $table->unsignedBigInteger('bank_correspondent_id')->nullable();
            $table->foreign('bank_correspondent_id')->references('id')->on('bank_correspondents')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_banks', function (Blueprint $table) {
            $table->dropForeign(['bank_correspondent_id']);
            $table->dropColumn('bank_correspondent_id');
        });
    }
}
