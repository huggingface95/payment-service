<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountryIdAndAddedFromFieldsToApplicantDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_documents', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('added_from')->nullable();

            $table->foreign('country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicant_documents', function (Blueprint $table) {
            $table->dropColumn('country_id');
            $table->dropColumn('added_from');
        });
    }
}
