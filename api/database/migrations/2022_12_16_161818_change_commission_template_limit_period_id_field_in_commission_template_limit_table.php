<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCommissionTemplateLimitPeriodIdFieldInCommissionTemplateLimitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commission_template_limit', function (Blueprint $table) {
            $table->unsignedBigInteger('commission_template_limit_period_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commission_template_limit', function (Blueprint $table) {
            $table->unsignedBigInteger('commission_template_limit_period_id')->nullable(false)->change();
        });
    }
}
