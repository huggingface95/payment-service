<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropCanCreatePaymentAndCanSignPaymentColumnsInApplicantBankingAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_banking_access', function (Blueprint $table) {
            $table->dropColumn(['can_create_payment', 'can_sign_payment']);
            $table->unsignedBigInteger('role_id')->nullable();

            $table->foreign('role_id')->references('id')->on('roles');
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
            $table->dropForeign(['role_id']);

            $table->dropColumn('role_id');
            $table->boolean('can_create_payment')->default('false');
            $table->boolean('can_sign_payment')->default('false');
        });
    }
}
