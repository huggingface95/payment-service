<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsSwiftSortCodeProviderAddressAboutInPaymentProviderIbansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_provider_ibans', function (Blueprint $table) {
            $table->string('swift')->nullable();
            $table->string('sort_code')->nullable();
            $table->string('provider_address')->nullable();
            $table->string('about')->nullable();
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
            $table->dropColumn(['swift', 'sort_code', 'provider_address', 'about']);
        });
    }
}
