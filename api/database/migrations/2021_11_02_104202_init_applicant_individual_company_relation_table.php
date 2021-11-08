<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ApplicantIndividualCompanyRelation;

class InitApplicantIndividualCompanyRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ApplicantIndividualCompanyRelation::create([
            'name'=>'Director'
        ]);
        ApplicantIndividualCompanyRelation::create([
            'name'=>'Shareholder'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
