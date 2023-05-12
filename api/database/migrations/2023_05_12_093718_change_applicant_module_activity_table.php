<?php

use App\Models\ApplicantModuleActivity;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeApplicantModuleActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ApplicantModuleActivity::truncate();
        Schema::table('applicant_module_activity', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table->dropColumn('applicant_type');

            $table->boolean('individual')->default('false');
            $table->boolean('corporate')->default('false');

            $table->foreign('applicant_id')->references('id')->on('applicant_individual')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicant_module_activity', function (Blueprint $table) {
            $table->dropColumn('individual');
            $table->dropColumn('corporate');

            $table->boolean('is_active')->default('false');
            $table->enum('applicant_type', [class_basename(ApplicantIndividual::class), class_basename(ApplicantCompany::class)]);

            $table->dropForeign(['applicant_id']);
        });
    }
}
