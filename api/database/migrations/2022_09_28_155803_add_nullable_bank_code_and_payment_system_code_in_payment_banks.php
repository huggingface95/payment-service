<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullableBankCodeAndPaymentSystemCodeInPaymentBanks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_banks', function (Blueprint $table) {
            $table->string('bank_code')->nullable()->change();
            $table->string('payment_system_code')->nullable()->change();
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
            $table->string('bank_code')->change();
            $table->string('payment_system_code')->change();
        });
    }
}
