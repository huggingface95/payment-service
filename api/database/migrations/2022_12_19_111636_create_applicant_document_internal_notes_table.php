<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantDocumentInternalNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_document_internal_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('applicant_document_id');
            $table->unsignedBigInteger('member_id');
            $table->text('note');
            $table->timestamps();

            $table->foreign('applicant_document_id')->references('id')->on('applicant_documents');
            $table->foreign('member_id')->references('id')->on('members');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicant_document_internal_notes');
    }
}
