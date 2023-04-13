<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRevenueAccountIdFieldToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('revenue_account_id')->nullable();
            $table->unsignedBigInteger('transfer_id')->nullable()->change();
            changeEnumField('transactions', 'transfer_type', [class_basename(TransferIncoming::class), class_basename(TransferOutgoing::class)], true);
            changeEnumField('transactions', 'txtype', ['income', 'outgoing', 'fee', 'internal', 'exchange', 'revenue']);

            $table->foreign('revenue_account_id')->references('id')->on('company_revenue_accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['revenue_account_id']);
            $table->unsignedBigInteger('transfer_id')->change();
            changeEnumField('transactions', 'transfer_type', [class_basename(TransferIncoming::class), class_basename(TransferOutgoing::class)]);
            changeEnumField('transactions', 'txtype', ['income', 'outgoing', 'fee', 'internal', 'exchange']);

            $table->dropColumn('revenue_account_id');
        });
    }
}
