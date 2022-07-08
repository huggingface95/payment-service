<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionTemplateRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commission_template_regions', function (Blueprint $table) {
            $table->unsignedBigInteger('commission_template_id');
            $table->unsignedBigInteger('region_id');
            $table->foreign('commission_template_id')->references('id')->on('commission_template')->onDelete('cascade');
            $table->foreign('region_id')->references('id')->on('regions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commission_template_regions');
    }
}
