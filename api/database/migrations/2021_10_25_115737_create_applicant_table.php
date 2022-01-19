<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_individual', function (Blueprint $table) {
            $table->id();
            $table->string('first_name',255);
            $table->string('last_name',255);
            $table->string('middle_name',255)->nullable();
            $table->string('email',255)->unique();
            $table->string('url',255)->nullable();
            $table->string('phone',255);
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('citizenship_country_id')->nullable();
            $table->string('state',255)->nullable();
            $table->string('city',100)->nullable();
            $table->string('address',255)->nullable();
            $table->string('zip',20)->nullable();
            $table->string('nationality',100)->nullable();
            $table->unsignedBigInteger('birth_country_id')->nullable();
            $table->string('birth_state',255)->nullable();
            $table->string('birth_city',100)->nullable();
            $table->date('birth_at')->nullable();
            $table->unsignedSmallInteger('sex')->nullable();
            $table->string('password_hash',255);
            $table->string('password_salt',255);
            $table->jsonb('profile_additional_fields')->nullable();
            $table->jsonb('personal_additional_fields')->nullable();
            $table->jsonb('contacts_additional_fields')->nullable();
            $table->unsignedBigInteger('applicant_status_id')->nullable();
            $table->unsignedBigInteger('applicant_state_id')->nullable();
            $table->unsignedBigInteger('applicant_state_reason_id')->nullable();
            $table->unsignedBigInteger('applicant_risk_level_id')->nullable();
            $table->unsignedBigInteger('account_manager_member_id')->nullable();
            $table->timestamps();
            $table->foreign('applicant_status_id')->references('id')->on('applicant_status');
            $table->foreign('applicant_state_id')->references('id')->on('applicant_state');
            $table->foreign('applicant_state_reason_id')->references('id')->on('applicant_state_reason');
            $table->foreign('applicant_risk_level_id')->references('id')->on('applicant_risk_level');
            $table->foreign('account_manager_member_id')->references('id')->on('members');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->foreign('citizenship_country_id')->references('id')->on('countries');
            $table->foreign('birth_country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicant');
    }
}
