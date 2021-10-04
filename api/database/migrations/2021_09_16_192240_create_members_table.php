<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('first_name',100);
            $table->string('last_name',100);
            $table->string('email',255);
            $table->enum('sex',['male','female'])->nullable();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('language_id')->nullable();
            $table->unsignedBigInteger('member_group_role_id');
            $table->unsignedBigInteger('two_factor_auth_setting_id');
            $table->unsignedBigInteger('department_position_id')->nullable();
            $table->unsignedBigInteger('is_active')->default(false);
            $table->string('password_hash',255);
            $table->string('password_salt',255);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('member_group_role_id')->references('id')->on('group_role');
            $table->foreign('two_factor_auth_setting_id')->references('id')->on('two_factor_auth_settings');
            $table->foreign('department_position_id')->references('id')->on('department_position');
            $table->foreign('country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
