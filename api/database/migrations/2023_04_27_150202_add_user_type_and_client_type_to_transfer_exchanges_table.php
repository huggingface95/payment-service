<?php

use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\Members;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserTypeAndClientTypeToTransferExchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_exchanges', function (Blueprint $table) {
            $table->enum('user_type', [class_basename(ApplicantIndividual::class), class_basename(Members::class)])->nullable();
            $table->enum('client_type', [class_basename(ApplicantIndividual::class), class_basename(ApplicantCompany::class)])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_exchanges', function (Blueprint $table) {
            $table->dropColumn(['user_type', 'client_type']);
        });
    }
}
