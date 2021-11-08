<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->string('email',255)->unique();
            $table->string('url',255);
            $table->string('phone',255);
            $table->unsignedBigInteger('country_id');
            $table->string('state',255)->nullable();
            $table->string('city',100)->nullable();
            $table->string('address',255)->nullable();
            $table->string('address2',255)->nullable();
            $table->string('office_address',255)->nullable();
            $table->string('zip',20)->nullable();
            $table->timestamp('reg_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('tax',100)->nullable();
            $table->string('reg_number',100)->nullable();
            $table->string('license_number',100)->nullable();
            $table->string('company_type',100)->nullable();
            $table->string('password_hash',255);
            $table->string('password_salt',255);
            $table->jsonb('info_additional_fields')->nullable();
            $table->jsonb('contacts_additional_fields')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('applicant_company_business_type_id')->nullable();
            $table->unsignedBigInteger('applicant_status_id')->nullable();
            $table->unsignedBigInteger('applicant_state_id')->nullable();
            $table->unsignedBigInteger('applicant_state_reason_id')->nullable();
            $table->unsignedBigInteger('account_manager_member_id')->nullable();

            $table->foreign('applicant_company_business_type_id')->references('id')->on('applicant_company_business_type');
            $table->foreign('applicant_status_id')->references('id')->on('applicant_status');
            $table->foreign('applicant_state_id')->references('id')->on('applicant_state');
            $table->foreign('applicant_state_reason_id')->references('id')->on('applicant_state_reason');
            $table->foreign('account_manager_member_id')->references('id')->on('members');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicant_companies');
    }
}
