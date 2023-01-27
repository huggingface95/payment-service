<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsedDailyLimitAndUsedMonthlyLimitToApplicantBankingAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_banking_access', function (Blueprint $table) {
            $table->decimal('used_daily_limit', 15, 5)->default(0);
            $table->decimal('used_monthly_limit', 15, 5)->default(0);
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
            $table->dropColumn('used_daily_limit');
            $table->dropColumn('used_monthly_limit');
        });
    }
}
