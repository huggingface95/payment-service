<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLedgerDayHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_ledger_day_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('revenue_account_id');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('currency_id');
            $table->decimal('amount', 15, 5);
            $table->decimal('revenue_balance', 15, 5)->default(0);
            $table->dateTime('created_at');

            $table->foreign('revenue_account_id')->references('id')->on('company_revenue_accounts');
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_ledger_day_histories');
    }
}
