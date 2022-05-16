<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteGroupIdInMembersAndIndividuals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign('members_member_group_role_id_foreign');
            $table->dropColumn('group_id');
        });
        Schema::table('applicant_individual', function (Blueprint $table) {
            $table->dropForeign('applicant_individual_member_group_role_id_foreign');
            $table->dropColumn('group_id');
        });
        Schema::table('applicant_companies', function (Blueprint $table) {
            $table->dropForeign('applicant_companies_member_group_role_id_foreign');
            $table->dropColumn('group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->nullable();
        });
        Schema::table('applicant_individual', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->nullable();
        });
        Schema::table('applicant_companies', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->nullable();
        });
    }
}
