<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsVereficationEmailFieldToApplicantIndividualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_individual', function (Blueprint $table) {
            $table->boolean('is_verification_email')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicant_individual', function (Blueprint $table) {
            $table->dropColumn('is_verification_email');
        });
    }
}
