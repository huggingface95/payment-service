<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeApplicantDocumentTagRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_document_tag_relation', function (Blueprint $table) {
            $table->unique(['applicant_document_id', 'applicant_document_tag_id']);
            $table->foreign('applicant_document_id')->references('id')->on('applicant_documents');
            $table->foreign('applicant_document_tag_id')->references('id')->on('applicant_document_tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicant_document_tag_relation', function (Blueprint $table) {
            $table->dropUnique(['applicant_document_id', 'applicant_document_tag_id']);
            $table->dropForeign(['applicant_document_id']);
            $table->dropForeign(['applicant_document_tag_id']);
        });
    }
}
