<?php

use App\Models\ApplicantIndividual;
use App\Models\Members;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManagedFieldsToTransfersHistoriesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_incoming_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('managed_id')->nullable();
            $table->enum('managed_type', [class_basename(ApplicantIndividual::class), class_basename(Members::class)])->nullable();
        });

        Schema::table('transfer_outgoing_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('managed_id')->nullable();
            $table->enum('managed_type', [class_basename(ApplicantIndividual::class), class_basename(Members::class)])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_incoming_histories', function (Blueprint $table) {
            $table->dropColumn('managed_id');
            $table->dropColumn('managed_type');
        });

        Schema::table('transfer_outgoing_histories', function (Blueprint $table) {
            $table->dropColumn('managed_id');
            $table->dropColumn('managed_type');
        });
    }
}
