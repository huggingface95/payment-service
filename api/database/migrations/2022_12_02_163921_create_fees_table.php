<?php

use App\Enums\FeeTransferTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->decimal('fee', 15, 5);
            $table->decimal('fee_pp', 15, 5);
            $table->unsignedBigInteger('fee_type_id');
            $table->unsignedBigInteger('transfer_id');
            $table->enum('transfer_type', [FeeTransferTypeEnum::OUTGOING->toString(), FeeTransferTypeEnum::INCOMING->toString()]);
            $table->unsignedBigInteger('operation_type_id');
            $table->unsignedBigInteger('member_id')->nullable();
            $table->unsignedInteger('status_id');
            $table->unsignedBigInteger('client_id');
            $table->enum('client_type', [class_basename(ApplicantIndividual::class), class_basename(ApplicantCompany::class)]);
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('price_list_fee_id');
            $table->timestamps();

            $table->foreign('fee_type_id')->references('id')->on('fee_types');
            $table->foreign('operation_type_id')->references('id')->on('operation_type');
            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('status_id')->references('id')->on('payment_status');
        });

        DB::statement("alter table fees add column fee_amount numeric(15,5) generated always as (fee + fee_pp) stored;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fees');
    }
}
