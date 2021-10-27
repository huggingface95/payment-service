<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name',255)->unique();
            $table->string('url',255)->nullable();
            $table->string('email',255);
            $table->string('company_number',100)->nullable();
            $table->string('contact_name',100)->nullable();
            $table->unsignedBigInteger('country_id');
            $table->string('zip',20)->nullable();
            $table->string('address',255)->nullable();
            $table->string('city',100)->nullable();
            $table->jsonb('additional_fields')->nullable();
            $table->timestamps();
            $table->foreign('country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
