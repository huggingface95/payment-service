<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->primary();
            $table->string('email_url', 255)->nullable();
            $table->string('email_jwt', 255)->nullable();
            $table->string('email_from', 255)->nullable();
            $table->string('logo_object_key', 255)->nullable();
            $table->boolean('show_own_logo')->default(false);
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_settings');
    }
}
