<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantCompanyLabels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_company_labels', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->string('hex_color_code', 10);
            $table->unsignedBigInteger('member_id');
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
        Schema::dropIfExists('applicant_company_labels');
    }
}
