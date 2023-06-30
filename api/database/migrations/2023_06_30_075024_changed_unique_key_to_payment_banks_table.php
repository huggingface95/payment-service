<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangedUniqueKeyToPaymentBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_banks', function (Blueprint $table) {
            $table->dropUnique('payment_system_id');
            $table->unique(['payment_system_id', 'name'], 'payment_system_id_name_unique');
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
            $table->dropUnique('payment_system_id_name_unique');
            $table->unique('payment_provider_id', 'payment_provider_id');
        });
    }
}
