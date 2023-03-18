<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldsSwiftSortCodeProviderAddressAboutInPaymentProviderIbansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_provider_ibans', function (Blueprint $table) {
            $table->string('swift', 20)->change();
            $table->string('sort_code', 20)->change();
            $table->string('provider_address', 255)->change();
            $table->string('about', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_provider_ibans', function (Blueprint $table) {
            $table->string('swift')->change();
            $table->string('sort_code')->change();
            $table->string('provider_address')->change();
            $table->string('about')->change();
        });
    }
}
