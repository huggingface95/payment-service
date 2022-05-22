<?php

use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountIndividualsCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_individuals_companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('clientable_id');
            $table->enum('clientable_type', [ApplicantIndividual::class, ApplicantCompany::class])->default(ApplicantIndividual::class);
            $table->unique(['account_id', 'client_id', 'client_type']);

            $table->foreign('account_id')->references('id')->on('accounts')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_individuals_companies');
    }
}
