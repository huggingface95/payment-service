<?php

use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetDefaultStatusIdToTransfersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_exchanges', function (Blueprint $table) {
            $table->unsignedSmallInteger('status_id')->default(PaymentStatusEnum::UNSIGNED->value)->change();
        });

        Schema::table('transfer_incomings', function (Blueprint $table) {
            $table->unsignedInteger('status_id')->default(PaymentStatusEnum::UNSIGNED->value)->change();
        });

        Schema::table('transfer_outgoings', function (Blueprint $table) {
            $table->unsignedInteger('status_id')->default(PaymentStatusEnum::UNSIGNED->value)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_exchanges', function (Blueprint $table) {
            $table->unsignedSmallInteger('status_id')->nullable(false)->change();
        });

        Schema::table('transfer_incomings', function (Blueprint $table) {
            $table->unsignedInteger('status_id')->nullable(false)->change();
        });

        Schema::table('transfer_outgoings', function (Blueprint $table) {
            $table->unsignedInteger('status_id')->nullable(false)->change();
        });
    }
}
