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
            $table->integer('company_id');
            $table->integer('country_id')->nullable();
            $table->integer('language_id')->nullable();
            $table->integer('member_group_role_id');
            $table->integer('two_factor_auth_setting_id');
            $table->integer('is_active')->default(false);
            $table->string('password_hash',255);
            $table->string('password_salt',255);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('member_group_role_id')->references('id')->on('group_role');
            $table->foreign('two_factor_auth_setting_id')->references('id')->on('two_factor_auth_settings');
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
