<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_limits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('commission_template_limit_type_id');
            $table->unsignedBigInteger('commission_template_limit_transfer_direction_id');
            $table->unsignedBigInteger('commission_template_limit_period_id');
            $table->unsignedBigInteger('commission_template_limit_action_type_id');
            $table->bigInteger('period_count')->nullable();
            $table->decimal('amount',15,5)->default(0);
            $table->unsignedBigInteger('currency_id')->nullable();


            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('commission_template_limit_type_id')->references('id')->on('commission_template_limit_type');
            $table->foreign('commission_template_limit_transfer_direction_id')->references('id')->on('commission_template_limit_transfer_direction');
            $table->foreign('commission_template_limit_period_id')->references('id')->on('commission_template_limit_period');
            $table->foreign('commission_template_limit_action_type_id')->references('id')->on('commission_template_limit_action_type');
            $table->foreign('currency_id')->references('id')->on('currencies');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_limits');
    }
}
