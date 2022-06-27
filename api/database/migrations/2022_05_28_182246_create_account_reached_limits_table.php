<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountReachedLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_reached_limits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->string('group_type');
            $table->string('client_name');
            $table->string('client_type');
            $table->string('transfer_direction');
            $table->string('limit_type');
            $table->integer('limit_value');
            $table->string('limit_currency');
            $table->integer('period');
            $table->decimal('amount', 15, 5)->default(0);
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_reached_limits');
    }
}
