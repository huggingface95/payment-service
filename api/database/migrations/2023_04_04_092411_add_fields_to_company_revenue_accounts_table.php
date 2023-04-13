<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToCompanyRevenueAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_revenue_accounts', function (Blueprint $table) {
            $table->unsignedInteger('currency_id');
            $table->decimal('balance', 15, 5)->default(0);

            $table->foreign('currency_id', 'currency_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_revenue_accounts', function (Blueprint $table) {
            $table->dropColumn('currency_id');
            $table->dropColumn('balance');

            $table->dropForeign(['currency_id']);
        });
    }
}
