<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIncorporateDateAndBasicInfoAdditionalFieldToApplicantCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_companies', function (Blueprint $table) {
            $table->timestamp('incorporate_date')->nullable();
            $table->jsonb('basic_info_additional_field')->nullable();
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
            $table->dropColumn(['incorporate_date', 'basic_info_additional_field']);
        });
    }
}
