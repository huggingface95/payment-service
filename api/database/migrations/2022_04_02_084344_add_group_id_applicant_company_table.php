<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroupIdApplicantCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_companies', function (Blueprint $table) {
            $table->unsignedBigInteger('member_group_role_id')->nullable();
            $table->foreign('member_group_role_id')->references('id')->on('group_role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicant_companies', function (Blueprint $table) {
            $table->dropColumn('member_group_role_id');
        });
    }
}
