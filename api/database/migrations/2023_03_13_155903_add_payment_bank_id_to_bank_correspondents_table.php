<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentBankIdToBankCorrespondentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_correspondents', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_bank_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_correspondents', function (Blueprint $table) {
            $table->dropColumn('payment_bank_id');
        });
    }
}
