<?php

use App\Models\ApplicantIndividualCompanyPosition;
use Illuminate\Database\Migrations\Migration;

class InitApplicantIndividualCompanyPositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ApplicantIndividualCompanyPosition::create(['name'=>'Chief']);
        ApplicantIndividualCompanyPosition::create(['name'=>'Operating Officer']);
        ApplicantIndividualCompanyPosition::create(['name'=>'Chief Executive Officer']);
        ApplicantIndividualCompanyPosition::create(['name'=>'Treasure']);
        ApplicantIndividualCompanyPosition::create(['name'=>'Director']);
        ApplicantIndividualCompanyPosition::create(['name'=>'Secretary']);
        ApplicantIndividualCompanyPosition::create(['name'=>'Managing director']);
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
