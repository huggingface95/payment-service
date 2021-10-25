<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionTemplateCommissionTemplateLimitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commission_template_limit_relation', function (Blueprint $table) {
            $table->unsignedBigInteger('commission_template_id');
            $table->unsignedBigInteger('commission_template_limit_id');
            $table->foreign('commission_template_id')->references('id')->on('commission_template')->onDelete('cascade');
            $table->foreign('commission_template_limit_id')->references('id')->on('commission_template_limit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commission_template_commission_template_limit');
    }
}
