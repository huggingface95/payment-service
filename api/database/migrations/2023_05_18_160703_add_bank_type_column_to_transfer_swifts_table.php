<?php

use App\Enums\TransferSwiftBankTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankTypeColumnToTransferSwiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_swifts', function (Blueprint $table) {
            $table->enum('bank_type', array_map(function ($enum) {
                return $enum->value;
            }, TransferSwiftBankTypeEnum::cases()))->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_swifts', function (Blueprint $table) {
            $table->dropColumn('bank_type');
        });
    }
}
