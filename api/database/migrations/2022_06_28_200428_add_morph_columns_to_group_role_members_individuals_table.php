<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMorphColumnsToGroupRoleMembersIndividualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_role_members_individuals', function (Blueprint $table) {
            $table->dropUnique('group_role_members_individuals_group_role_id_user_id_unique');
            $table->id()->first();
            $table->enum('user_type', [Members::class, ApplicantIndividual::class, ApplicantCompany::class])->default(Members::class);

            $table->unique(['group_role_id', 'user_type', 'user_id']);
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
            $table->dropUnique('group_role_members_individuals_group_role_id_user_type_user_id_');
            $table->dropColumn('id');
            $table->dropColumn('user_type');
        });
    }
}
