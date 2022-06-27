<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('currency_src_id');
            $table->unsignedBigInteger('currency_dst_id');
            $table->unsignedBigInteger('account_src_id')->nullable();
            $table->unsignedBigInteger('account_dst_id')->nullable();
            $table->decimal('balance_prev', 15, 5);
            $table->decimal('balance_next', 15, 5);
            $table->decimal('amount', 15, 5);
            $table->enum('txtype', ['income', 'outgoing', 'fee', 'internal']);
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('currency_src_id')->references('id')->on('currencies');
            $table->foreign('currency_dst_id')->references('id')->on('currencies');
            $table->foreign('account_src_id')->references('id')->on('accounts');
            $table->foreign('account_dst_id')->references('id')->on('accounts');
            $table->foreign('payment_id')->references('id')->on('payments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
