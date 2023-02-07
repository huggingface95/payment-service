<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroupTypeIdToMemberAccessLimitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_access_limitations', function (Blueprint $table) {
            $table->unsignedBigInteger('group_type_id')->nullable();
            $table->unsignedBigInteger('group_role_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_access_limitations', function (Blueprint $table) {
            $table->dropColumn('group_type_id');
        });
    }
}
