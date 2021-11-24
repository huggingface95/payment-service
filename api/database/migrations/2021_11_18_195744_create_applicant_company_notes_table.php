<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantCompanyNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_company_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('applicant_company_id');
            $table->unsignedBigInteger('member_id');
            $table->text('note');
            $table->timestamps();
            $table->foreign('applicant_company_id')->references('id')->on('applicant_companies')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicant_company_notes');
    }
}
