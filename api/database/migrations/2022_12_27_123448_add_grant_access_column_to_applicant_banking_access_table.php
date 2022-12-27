<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGrantAccessColumnToApplicantBankingAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_banking_access', function (Blueprint $table) {
            $table->boolean('grant_access')->default('false');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicant_banking_access', function (Blueprint $table) {
            $table->dropColumn('grant_access');
        });
    }
}
