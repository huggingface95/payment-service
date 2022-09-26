<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLogoToPaymentProviderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_provider', function (Blueprint $table) {
            $table->dropColumn('logo_key');
            $table->unsignedBigInteger('logo_id')->nullable();

            $table->foreign('logo_id')->references('id')->on('files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_provider', function (Blueprint $table) {
            $table->string('logo_key',256)->nullable();
            $table->dropColumn('logo_id');
        });
    }
}
