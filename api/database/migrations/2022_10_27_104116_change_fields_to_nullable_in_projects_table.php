<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldsToNullableInProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('url', 255)->nullable()->change();
            $table->string('description', 255)->nullable()->change();
            $table->string('client_url', 255)->nullable()->change();
            $table->string('support_email', 255)->nullable()->change();
            $table->string('login_url', 255)->nullable()->change();
            $table->string('sms_sender_name', 255)->nullable()->change();
            $table->unsignedBigInteger('module_id')->nullable()->change();
            $table->unsignedBigInteger('avatar_id')->nullable()->change();
            $table->unsignedBigInteger('state_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('url', 255)->change();
            $table->string('description', 255)->change();
            $table->string('client_url', 255)->change();
            $table->string('support_email', 255)->change();
            $table->string('login_url', 255)->change();
            $table->string('sms_sender_name', 255)->change();
            $table->unsignedBigInteger('module_id')->change();
            $table->unsignedBigInteger('avatar_id')->change();
            $table->unsignedBigInteger('state_id')->change();
        });
    }
}
