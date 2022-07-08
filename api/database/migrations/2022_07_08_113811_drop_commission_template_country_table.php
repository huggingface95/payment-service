<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropCommissionTemplateCountryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('commission_template_country');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('commission_template_country', function (Blueprint $table) {
            $table->unsignedBigInteger('commission_template_id');
            $table->unsignedBigInteger('country_id');
        });
    }
}
