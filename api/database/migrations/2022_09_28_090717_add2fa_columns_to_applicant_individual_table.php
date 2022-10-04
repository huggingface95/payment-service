<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add2faColumnsToApplicantIndividualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_individual', function (Blueprint $table) {
            $table->boolean('is_active')->default('false');
            $table->string('google2fa_secret')->nullable();
            $table->string('security_pin')->nullable();
            $table->jsonb('backup_codes')->nullable();

            $table->renameColumn('two_factor_auth_id', 'two_factor_auth_setting_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicant_individual', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table->dropColumn('google2fa_secret');
            $table->dropColumn('security_pin');
            $table->dropColumn('backup_codes');

            $table->renameColumn('two_factor_auth_setting_id', 'two_factor_auth_id');
        });
    }
}
