<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankCorrespondentRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_correspondent_regions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_correspondent_id');
            $table->unsignedBigInteger('region_id');

            $table->foreign('bank_correspondent_id')->references('id')->on('bank_correspondents')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('bank_correspondent_regions');
    }
}
