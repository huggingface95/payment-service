<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropGroupRoleIdToMemberAccessLimitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_access_limitations', function (Blueprint $table) {
            $table->dropUnique('member_access_limitations_member_id_group_role_id_unique');
            $table->dropForeign('member_access_limitations_group_role_id_foreign');
            $table->dropColumn('group_role_id');
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
            $table->unsignedBigInteger('group_role_id');
        });
    }
}
