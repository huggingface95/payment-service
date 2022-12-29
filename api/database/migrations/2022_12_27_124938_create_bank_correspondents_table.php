<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankCorrespondentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_correspondents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('payment_system_id');
            $table->boolean('is_active');
            $table->timestamps();

            $table->foreign('payment_system_id')->references('id')->on('payment_system');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_correspondents');
    }
}
