<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 255);
            $table->unsignedSmallInteger('sex')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('language_id')->nullable();
            $table->unsignedBigInteger('member_group_role_id');
            $table->unsignedBigInteger('two_factor_auth_setting_id')->default(1);
            $table->unsignedBigInteger('department_position_id')->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('password_hash', 255);
            $table->string('password_salt', 255);
            $table->jsonb('additional_fields')->nullable();
            $table->jsonb('additional_info_fields')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('member_group_role_id')->references('id')->on('group_role');
            $table->foreign('two_factor_auth_setting_id')->references('id')->on('two_factor_auth_settings');
            $table->foreign('department_position_id')->references('id')->on('department_position');
            $table->foreign('country_id')->references('id')->on('countries');
        });

        DB::raw("alter table members add column fullname varchar(255) GENERATED ALWAYS AS (first_name || ' '|| last_name) STORED");
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
