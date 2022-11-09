<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldsInApplicantIndividualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_individual', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id')->nullable()->change();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('group_type_id')->nullable();

            $table->foreign('group_type_id')->references('id')->on('group_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicant_individual', function (Blueprint $table) {
            $table->dropForeign(['group_type_id']);

            $table->unsignedBigInteger('country_id')->change();
            $table->dropColumn('project_id');
            $table->dropColumn('group_type_id');
        });
    }
}
