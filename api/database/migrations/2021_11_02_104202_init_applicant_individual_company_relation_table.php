<?php

use App\Models\ApplicantIndividualCompanyRelation;
use Illuminate\Database\Migrations\Migration;

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
            'name'=>'Director',
        ]);
        ApplicantIndividualCompanyRelation::create([
            'name'=>'Shareholder',
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
