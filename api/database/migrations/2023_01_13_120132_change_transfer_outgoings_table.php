<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTransferOutgoingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_outgoings', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('group_type_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('price_list_id');
            $table->unsignedBigInteger('price_list_fee_id');

            $table->string('recipient_account', 255)->nullable()->change();
            $table->string('recipient_bank_name', 255)->nullable()->change();
            $table->string('recipient_bank_address', 255)->nullable()->change();
            $table->string('recipient_name', 255)->nullable()->change();
            $table->string('recipient_city', 255)->nullable()->change();
            $table->string('recipient_address', 255)->nullable()->change();
            $table->string('recipient_state', 255)->nullable()->change();
            $table->string('recipient_zip', 255)->nullable()->change();
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
        Schema::table('transfer_outgoings', function (Blueprint $table) {
            $table->dropColumn('group_id');
            $table->dropColumn('group_type_id');
            $table->dropColumn('project_id');
            $table->dropColumn('price_list_id');
            $table->dropColumn('price_list_fee_id');

            $table->string('recipient_account', 255)->change();
            $table->string('recipient_bank_name', 255)->change();
            $table->string('recipient_bank_address', 255)->change();
            $table->string('recipient_name', 255)->change();
            $table->string('recipient_city', 255)->change();
            $table->string('recipient_address', 255)->change();
            $table->string('recipient_state', 255)->change();
            $table->string('recipient_zip', 255)->change();
            $table->string('bank_message', 255)->change();
        });
    }
}
