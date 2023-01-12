<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameApplicantModulesToModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('applicant_modules', 'modules');

        Schema::table('applicant_individual_modules', function (Blueprint $table) {
            $table->dropForeign(['applicant_module_id']);
            $table->renameColumn('applicant_module_id', 'module_id');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
        });

        Schema::table('applicant_company_modules', function (Blueprint $table) {
            $table->dropForeign(['applicant_module_id']);
            $table->renameColumn('applicant_module_id', 'module_id');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
        });

        Schema::table('group_role', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->foreign('module_id')->references('id')->on('modules');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->foreign('module_id')->references('id')->on('modules');
        });

        Schema::table('member_access_limitations', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->foreign('module_id')->references('id')->on('modules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('modules', 'applicant_modules');

        Schema::table('applicant_individual_modules', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->renameColumn('module_id', 'applicant_module_id');
            $table->foreign('applicant_module_id')->references('id')->on('applicant_modules')->onDelete('cascade');
        });

        Schema::table('applicant_company_modules', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->renameColumn('module_id', 'applicant_module_id');
            $table->foreign('applicant_module_id')->references('id')->on('applicant_modules')->onDelete('cascade');
        });

        Schema::table('group_role', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->foreign('module_id')->references('id')->on('applicant_modules');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->foreign('module_id')->references('id')->on('applicant_modules');
        });

        Schema::table('member_access_limitations', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->foreign('module_id')->references('id')->on('applicant_modules');
        });
    }
}
