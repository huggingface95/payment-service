<?php

use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\Members;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionUserLimitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_user_limitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permission_id');
            $table->enum('user_type', [class_basename(ApplicantIndividual::class), class_basename(Members::class), class_basename(ApplicantCompany::class)]);
            $table->unsignedBigInteger('user_id');
            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->unique(['permission_id', 'user_type', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_user_limitations');
    }
}
