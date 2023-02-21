<?php

use App\Models\TransferIncoming;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['payment_id']);
            $table->dropColumn('payment_id');

            $table->unsignedBigInteger('transfer_id');
            $table->enum('transfer_type', [class_basename(TransferIncoming::class), class_basename(TransferOutgoing::class)]);
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
            $table->unsignedBigInteger('payment_id');
            $table->foreign('payment_id')->references('id')->on('payments');

            $table->dropColumn('transfer_id');
            $table->dropColumn('transfer_type');
        });
    }
}
