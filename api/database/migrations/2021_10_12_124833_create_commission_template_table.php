<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCommissionTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commission_template', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->string('description',512)->nullable();
            $table->unsignedBigInteger('payment_provider_id');
            $table->boolean('is_active')->default(false);
            $table->foreign('payment_provider_id')->references('id')->on('payment_provider');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commission_template');
    }
}
