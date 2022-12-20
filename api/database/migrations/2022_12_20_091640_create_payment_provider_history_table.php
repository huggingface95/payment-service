<?php

use App\Enums\FeeTransferTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentProviderHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_provider_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_provider_id');
            $table->unsignedBigInteger('transfer_id');
            $table->enum('transfer_type', [FeeTransferTypeEnum::OUTGOING->toString(), FeeTransferTypeEnum::INCOMING->toString()]);
            $table->jsonb('provider_response')->nullable();
            $table->timestamps();

            $table->foreign('payment_provider_id')->references('id')->on('payment_provider');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_provider_histories');
    }
}
