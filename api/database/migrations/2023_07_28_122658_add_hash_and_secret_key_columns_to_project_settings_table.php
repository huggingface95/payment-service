<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHashAndSecretKeyColumnsToProjectSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_settings', function (Blueprint $table) {
            $table->string('secret_key', 16)->nullable();
            $table->string('hash')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_settings', function (Blueprint $table) {
            $table->dropColumn(['secret_key', 'hash']);
        });
    }
}
