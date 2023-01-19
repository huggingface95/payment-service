<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameApplicantDocumentInternalNoteTagRelationTableToApplicantDocumentTagRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('applicant_document_internal_note_tag_relation');

        Schema::create('applicant_document_tag_relation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('applicant_document_id');
            $table->unsignedBigInteger('applicant_document_tag_id');

            $table->unique(['applicant_document_id', 'applicant_document_tag_id']);
            $table->foreign('applicant_document_id', 'applicant_document_id')->references('id')->on('applicant_documents');
            $table->foreign('applicant_document_tag_id', 'applicant_document_tag_id')->references('id')->on('applicant_document_tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicant_document_tag_relation');

        Schema::create('applicant_document_internal_note_tag_relation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('applicant_document_internal_note_id');
            $table->unsignedBigInteger('applicant_document_tag_id');

            $table->unique(['applicant_document_internal_note_id', 'applicant_document_tag_id']);
            $table->foreign('applicant_document_internal_note_id', 'applicant_document_internal_note_id')->references('id')->on('applicant_document_internal_notes');
            $table->foreign('applicant_document_tag_id', 'applicant_document_tag_id')->references('id')->on('applicant_document_tags');
        });
    }
}
