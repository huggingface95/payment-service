<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldsInMemberAccessLimitationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_access_limitations', function (Blueprint $table) {
            $table->dropForeign(['commission_template_id']);
            $table->dropColumn('commission_template_id');

            $table->unique(['member_id', 'group_role_id']);

            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('module_id');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('payment_provider_id')->nullable();
            $table->boolean('see_own_applicants')->default('false');

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('module_id')->references('id')->on('applicant_modules');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('payment_provider_id')->references('id')->on('payment_provider');
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
            $table->unsignedBigInteger('commission_template_id');
            $table->foreign('commission_template_id')->references('id')->on('commission_template')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->unique(['member_id', 'group_role_id', 'commission_template_id']);

            $table->dropForeign(['company_id']);
            $table->dropForeign(['module_id']);
            $table->dropForeign(['project_id']);
            $table->dropForeign(['payment_provider_id']);

            $table->dropColumn(['company_id', 'module_id', 'project_id', 'payment_provider_id', 'see_own_applicants']);
        });
    }
}
