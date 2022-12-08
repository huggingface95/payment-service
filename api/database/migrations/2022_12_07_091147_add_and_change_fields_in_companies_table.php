<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAndChangeFieldsInCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('reg_number', 100)->nullable();
            $table->renameColumn('additional_fields', 'additional_fields_info');
            $table->jsonb('additional_fields_basic')->nullable();
            $table->jsonb('additional_fields_settings')->nullable();
            $table->jsonb('additional_fields_data')->nullable();
            $table->string('tax_id', 20)->nullable()->change();
            $table->string('member_verify_url')->nullable();
            $table->string('backoffice_login_url')->nullable();
            $table->string('backoffice_forgot_password_url')->nullable();
            $table->string('backoffice_support_url')->nullable();
            $table->string('backoffice_support_email')->nullable();
            $table->string('vv_token')->nullable();
            $table->unsignedBigInteger('logo_id')->nullable();
            $table->foreign('logo_id')->references('id')->on('files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('reg_number');
            $table->renameColumn('additional_fields_info', 'additional_fields');
            $table->dropColumn('additional_fields_basic');
            $table->dropColumn('additional_fields_settings');
            $table->dropColumn('additional_fields_data');
            $table->bigInteger('tax_id')->nullable()->change();
            $table->dropColumn('member_verify_url');
            $table->dropColumn('backoffice_login_url');
            $table->dropColumn('backoffice_forgot_password_url');
            $table->dropColumn('backoffice_support_url');
            $table->dropColumn('backoffice_support_email');
            $table->dropColumn('vv_token');
            $table->dropForeign('logo_id');
            $table->dropColumn('logo_id');
        });
    }
}
