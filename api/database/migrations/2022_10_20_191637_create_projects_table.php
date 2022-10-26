<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('url', 255);
            $table->string('description', 255);
            $table->string('client_url', 255);
            $table->string('support_email', 255);
            $table->string('login_url', 255);
            $table->string('sms_sender_name', 255);
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('module_id');
            $table->unsignedBigInteger('avatar_id');
            $table->unsignedBigInteger('state_id');
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('module_id')->references('id')->on('applicant_modules');
            $table->foreign('avatar_id')->references('id')->on('files');
            $table->foreign('state_id')->references('id')->on('states');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
