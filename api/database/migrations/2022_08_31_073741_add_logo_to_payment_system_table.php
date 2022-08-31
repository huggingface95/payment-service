<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLogoToPaymentSystemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_system', function (Blueprint $table) {
            $table->string('description')->nullable();
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
        Schema::table('payment_system', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('logo_id');
        });
    }
}
