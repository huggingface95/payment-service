<?php

use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('document_type_id');
            $table->unsignedInteger('document_state_id');
            $table->unsignedBigInteger('file_id');
            $table->unsignedBigInteger('applicant_id');
            $table->enum('applicant_type', [ApplicantIndividual::class, ApplicantCompany::class])->default(ApplicantIndividual::class);
            $table->unsignedBigInteger('company_id');
            $table->string('info')->nullable();
            $table->timestamps();

            $table->foreign('document_type_id')->references('id')->on('document_types');
            $table->foreign('document_state_id')->references('id')->on('document_states');
            $table->foreign('file_id')->references('id')->on('files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicant_documents');
    }
}
