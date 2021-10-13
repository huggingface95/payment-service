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

        DB::statement('ALTER TABLE commission_template ADD COLUMN country_id integer[]  DEFAULT ARRAY[]::integer[] NOT NULL');
        DB::statement('ALTER TABLE commission_template ADD COLUMN currency_id integer[]  DEFAULT ARRAY[]::integer[] NOT NULL');
        DB::statement('ALTER TABLE commission_template ADD COLUMN commission_template_limit_id integer[]  DEFAULT ARRAY[]::integer[] NOT NULL');
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
