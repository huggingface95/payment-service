<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLedgerSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_ledger_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->time('end_of_day_time')->nullable();
            $table->smallInteger('end_of_week_day')->nullable();
            $table->time('end_of_week_time')->nullable();
            $table->smallInteger('end_of_month_day')->nullable();
            $table->time('end_of_month_time')->nullable();

            $table->unique('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_ledger_settings');
    }
}
