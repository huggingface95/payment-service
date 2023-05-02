<?php

use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantModuleActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_module_activity', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('applicant_id');
            $table->enum('applicant_type', [class_basename(ApplicantIndividual::class), class_basename(ApplicantCompany::class)]);
            $table->unsignedBigInteger('module_id');
            $table->boolean('is_active')->default(false);

            $table->unique(['applicant_id', 'applicant_type', 'module_id']);
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicant_module_activity');
    }
}
