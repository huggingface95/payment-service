<?php

use App\Models\TransferIncoming;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceListIdAndPriceListFeeIdToTransferIncomingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        TransferIncoming::truncate();

        Schema::table('transfer_incomings', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('group_type_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('price_list_id');
            $table->unsignedBigInteger('price_list_fee_id');

            $table->string('sender_account', 255)->nullable()->change();
            $table->string('sender_bank_name', 255)->nullable()->change();
            $table->string('sender_bank_address', 255)->nullable()->change();
            $table->unsignedInteger('sender_bank_country_id')->nullable()->change();
            $table->string('sender_name', 255)->nullable()->change();
            $table->unsignedInteger('sender_country_id')->nullable()->change();
            $table->string('sender_city', 255)->nullable()->change();
            $table->string('sender_address', 255)->nullable()->change();
            $table->string('sender_state', 255)->nullable()->change();
            $table->string('sender_zip', 255)->nullable()->change();
            $table->string('bank_message', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_incomings', function (Blueprint $table) {
            $table->dropColumn('group_id');
            $table->dropColumn('group_type_id');
            $table->dropColumn('project_id');
            $table->dropColumn('price_list_id');
            $table->dropColumn('price_list_fee_id');

            $table->string('sender_account', 255)->change();
            $table->string('sender_bank_name', 255)->change();
            $table->string('sender_bank_address', 255)->change();
            $table->unsignedBigInteger('sender_bank_country_id')->change();
            $table->string('sender_name', 255)->change();
            $table->unsignedBigInteger('sender_country_id')->change();
            $table->string('sender_city', 255)->change();
            $table->string('sender_address', 255)->change();
            $table->string('sender_state', 255)->change();
            $table->string('sender_zip', 255)->change();
            $table->string('bank_message', 255)->change();
        });
    }
}
