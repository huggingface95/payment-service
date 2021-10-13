<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionTemplateBusinessActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commission_template_business_activity', function (Blueprint $table) {
            $table->unsignedBigInteger('commission_template_id');
            $table->unsignedBigInteger('business_activity_id');
            $table->unique(['commission_template_id','business_activity_id']);
            $table->foreign('commission_template_id')->references('id')->on('commission_template')->onDelete('cascade');
            $table->foreign('business_activity_id')->references('id')->on('business_activity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commission_template_business_activity');
    }
}
