<?php

use App\Enums\ApplicantVerificationStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIsVerificationPhoneFieldInApplicantCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_companies', function (Blueprint $table) {
            $table->dropColumn('is_verification_phone');

            $table->unsignedInteger('email_verification_status_id')->default(ApplicantVerificationStatusEnum::NOT_VERIFIED->value);
            $table->unsignedInteger('phone_verification_status_id')->default(ApplicantVerificationStatusEnum::NOT_VERIFIED->value);


            $table->foreign('email_verification_status_id')->references('id')->on('applicant_verification_statuses');
            $table->foreign('phone_verification_status_id')->references('id')->on('applicant_verification_statuses');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicant_companies', function (Blueprint $table) {
            $table->dropForeign(['email_verification_status_id']);
            $table->dropForeign(['phone_verification_status_id']);
            $table->dropColumn(['email_verification_status_id', 'phone_verification_status_id']);

            $table->boolean('is_verification_phone')->default(false);
        });
    }
}
