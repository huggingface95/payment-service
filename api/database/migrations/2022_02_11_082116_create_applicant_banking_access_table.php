<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantBankingAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_banking_access', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('applicant_individual_id');
            $table->unsignedBigInteger('applicant_company_id');
            $table->unsignedBigInteger('member_id');
            $table->boolean('can_create_payment')->default('false');
            $table->boolean('can_sign_payment')->default('false');
            $table->boolean('contact_administrator')->default('false');
            $table->decimal('daily_limit', 8, 2);
            $table->decimal('monthly_limit', 8, 2);
            $table->decimal('operation_limit', 8, 2);
            $table->foreign('applicant_individual_id')->references('id')->on('applicant_individual');
            $table->foreign('applicant_company_id')->references('id')->on('applicant_companies');
            $table->foreign('member_id')->references('id')->on('members');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicant_banking_access');
    }
}
