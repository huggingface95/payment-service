<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeForeignKeysOnApplicantDocumentRelatedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_document_internal_notes', function (Blueprint $table) {
            $table->dropForeign(['applicant_document_id']);
            
            $table->foreign('applicant_document_id', 'applicant_document_id')
                ->references('id')
                ->on('applicant_documents')
                ->onDelete('cascade');
        });

        Schema::table('kyc_timeline', function (Blueprint $table) {
            $table->dropForeign(['document_id']);

            $table->foreign('document_id')
                ->references('id')
                ->on('applicant_documents')
                ->onDelete('cascade');
        });

        Schema::table('applicant_document_tag_relation', function (Blueprint $table) {
            $table->dropForeign('applicant_document_id');
            $table->dropForeign('applicant_document_tag_id');

            $table->foreign('applicant_document_id', 'applicant_document_id')
                ->references('id')
                ->on('applicant_documents')
                ->onDelete('cascade');

            $table->foreign('applicant_document_tag_id', 'applicant_document_tag_id')
                ->references('id')
                ->on('applicant_document_tags')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicant_document_internal_notes', function (Blueprint $table) {
            $table->dropForeign('applicant_document_id');

            $table->foreign('applicant_document_id', 'applicant_document_id')
                ->references('id')
                ->on('applicant_documents');
        });

        Schema::table('kyc_timeline', function (Blueprint $table) {
            $table->dropForeign(['document_id']);

            $table->foreign('document_id')
                ->references('id')
                ->on('applicant_documents');
        });

        Schema::table('applicant_document_tag_relation', function (Blueprint $table) {
            $table->dropForeign('applicant_document_tag_relation_applicant_document_id_foreign');
            $table->dropForeign('applicant_document_tag_id');

            $table->foreign('applicant_document_id', 'applicant_document_id')
                ->references('id')
                ->on('applicant_documents');

            $table->foreign('applicant_document_tag_id', 'applicant_document_tag_id')
                ->references('id')
                ->on('applicant_document_tags');
        });
    }
}
