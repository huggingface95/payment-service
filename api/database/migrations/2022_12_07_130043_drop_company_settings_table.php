<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropCompanySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('company_settings');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('company_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->primary();
            $table->string('email_url', 255)->nullable();
            $table->string('email_jwt', 255)->nullable();
            $table->string('email_from', 255)->nullable();
            $table->string('logo_object_key', 255)->nullable();
            $table->boolean('show_own_logo')->default(false);
            $table->foreign('company_id')->references('id')->on('companies');
            $table->string('member_verify_url')->nullable();
            $table->string('backoffice_login_url')->nullable();
            $table->string('backoffice_forgot_password_url')->nullable();
            $table->string('backoffice_support_url')->nullable();
            $table->string('backoffice_support_email')->nullable();
        });
    }
}
