<?php

use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKycTimelineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kyc_timeline', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->string('os');
            $table->string('browser');
            $table->string('ip');
            $table->string('action');
            $table->string('action_state');
            $table->string('tag');
            $table->enum('action_type', ['document_upload', 'document_state', 'verification', 'email']);
            $table->unsignedBigInteger('document_id')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('applicant_id');
            $table->enum('applicant_type', [class_basename(ApplicantIndividual::class), class_basename(ApplicantCompany::class)])->default(class_basename(ApplicantIndividual::class));
            $table->dateTime('created_at');

            $table->foreign('creator_id')->references('id')->on('members');
            $table->foreign('document_id')->references('id')->on('applicant_documents');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kyc_timeline');
    }
}
