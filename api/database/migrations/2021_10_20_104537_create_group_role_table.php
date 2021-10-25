<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_role', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('payment_provider_id')->nullable();
            $table->unsignedBigInteger('commission_template_id')->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('description',512)->nullable();
            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('role_id')->references('id')->on('roles');
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
        Schema::dropIfExists('group_role');
    }
}
