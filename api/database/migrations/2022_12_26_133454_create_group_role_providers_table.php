<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupRoleProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_role_providers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_role_id');
            $table->unsignedBigInteger('payment_provider_id');
            $table->unsignedBigInteger('commission_template_id');
            $table->boolean('is_default');

            $table->foreign('group_role_id')->references('id')->on('group_role')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('payment_provider_id')->references('id')->on('payment_provider');
            $table->foreign('commission_template_id')->references('id')->on('commission_template');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_role_providers');
    }
}
