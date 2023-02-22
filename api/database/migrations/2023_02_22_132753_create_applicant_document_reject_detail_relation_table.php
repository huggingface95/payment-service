<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantDocumentRejectDetailRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_document_reject_detail_relation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('applicant_document_reject_detail_id');
            $table->unsignedBigInteger('applicant_document_tag_id');

            $table->foreign('applicant_document_reject_detail_id')->references('id')->on('applicant_document_reject_details')->onDelete('cascade');
            $table->foreign('applicant_document_tag_id')->references('id')->on('applicant_document_tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicant_document_reject_detail_relation');
    }
}
