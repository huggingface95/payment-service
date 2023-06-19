<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountNumberNcsNumberFildesInPaymentBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_banks', function (Blueprint $table) {
            $table->string('account_number')->nullable();
            $table->string('ncs_number')->nullable();
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
            $table->dropColumn([
                'account_number',
                'ncs_number',
            ]);
        });
    }
}
