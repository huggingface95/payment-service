<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserTypeUserIdUniqueGroupRoleMembersIndividualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_role_members_individuals', function (Blueprint $table) {
            $table->dropUnique('group_role_members_individuals_group_role_id_user_type_user_id_');
            $table->unique(['user_id', 'user_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_role_members_individuals', function (Blueprint $table) {
            $table->dropUnique('group_role_members_individuals_user_id_user_type_');
            $table->unique(['group_role_id', 'user_type', 'user_id']);
        });
    }
}
