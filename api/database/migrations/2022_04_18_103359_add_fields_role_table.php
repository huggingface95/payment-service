<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('group_type_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('group_type_id')->references('id')->on('group_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('group_type_id');
        });
    }
}
