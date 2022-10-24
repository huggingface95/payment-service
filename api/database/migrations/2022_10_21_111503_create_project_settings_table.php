<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('group_type_id')->nullable();
            $table->unsignedBigInteger('group_role_id')->nullable();
            $table->unsignedBigInteger('commission_template_id')->nullable();
            $table->unsignedBigInteger('payment_provider_id')->nullable();

            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('group_type_id')->references('id')->on('group_types');
            $table->foreign('group_role_id')->references('id')->on('group_role');
            $table->foreign('commission_template_id')->references('id')->on('commission_template');
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
        Schema::dropIfExists('project_settings');
    }
}
