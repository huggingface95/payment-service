<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotifyDeviceEmailFieldToApplicantIndividualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_individual', function (Blueprint $table) {
            $table->boolean('notify_device_email')->default(false);
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
            $table->dropColumn('notify_device_email');
        });
    }
}
