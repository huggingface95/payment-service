<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountryIdSwiftAccountNumberNcsNumberFiledsInBankCorrespondentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_correspondents', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('swift')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ncs_number')->nullable();

            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade');
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
            $table->dropForeign(['country_id']);

            $table->dropColumn([
                'country_id',
                'swift',
                'account_number',
                'ncs_number'
            ]);
        });
    }
}
