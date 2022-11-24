<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToCompanySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->string('member_verify_url')->nullable();
            $table->string('backoffice_login_url')->nullable();
            $table->string('backoffice_forgot_password_url')->nullable();
            $table->string('backoffice_support_url')->nullable();
            $table->string('backoffice_support_email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->dropColumn('member_verify_url');
            $table->dropColumn('backoffice_login_url');
            $table->dropColumn('backoffice_forgot_password_url');
            $table->dropColumn('backoffice_support_url');
            $table->dropColumn('backoffice_support_email');
        });
    }
}
