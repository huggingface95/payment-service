<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('group_type_id');
            $table->unsignedBigInteger('group_role_id');
            $table->unsignedBigInteger('payment_system_id');

            $table->foreign('group_type_id')->references('id')->on('group_types')->onUpdate('cascade');
            $table->foreign('group_role_id')->references('id')->on('group_role')->onUpdate('cascade');
            $table->foreign('payment_system_id')->references('id')->on('payment_system')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn(['group_type_id', 'group_role_id', 'payment_system_id']);
        });
    }
}
