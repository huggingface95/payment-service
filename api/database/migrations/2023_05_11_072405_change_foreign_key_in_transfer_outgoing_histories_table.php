<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeForeignKeyInTransferOutgoingHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_outgoing_histories', function (Blueprint $table) {
            $table->dropForeign(['transfer_id']);

            $table->foreign('transfer_id')->references('id')->on('transfer_outgoings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_outgoing_histories', function (Blueprint $table) {
            //
        });
    }
}
