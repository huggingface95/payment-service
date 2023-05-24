<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsChangedByIdChangedByTypeToTransferTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_outgoings', function (Blueprint $table) {
            $table->unsignedBigInteger('changed_by_id')->nullable();
            $table->enum('changed_by_type', [
                class_basename(ApplicantIndividual::class),
                class_basename(ApplicantCompany::class),
                class_basename(Members::class),
            ])->nullable();
        });

        Schema::table('transfer_incomings', function (Blueprint $table) {
            $table->unsignedBigInteger('changed_by_id')->nullable();
            $table->enum('changed_by_type', [
                class_basename(ApplicantIndividual::class),
                class_basename(ApplicantCompany::class),
                class_basename(Members::class),
            ])->nullable();
        });

        Schema::table('transfer_exchanges', function (Blueprint $table) {
            $table->unsignedBigInteger('changed_by_id')->nullable();
            $table->enum('changed_by_type', [
                class_basename(ApplicantIndividual::class),
                class_basename(ApplicantCompany::class),
                class_basename(Members::class),
            ])->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_outgoings', function (Blueprint $table) {
            $table->dropColumn(['changed_by_id', 'changed_by_type']);
        });

        Schema::table('transfer_incomings', function (Blueprint $table) {
            $table->dropColumn(['changed_by_id', 'changed_by_type']);
        });

        Schema::table('transfer_exchanges', function (Blueprint $table) {
            $table->dropColumn(['changed_by_id', 'changed_by_type']);
        });
    }
}
