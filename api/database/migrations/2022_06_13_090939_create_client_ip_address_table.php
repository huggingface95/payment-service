<?php

use App\Models\ApplicantIndividual;
use App\Models\Members;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientIpAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_ip_address', function (Blueprint $table) {
            $table->id();
            $table->text('ip_address')->nullable();
            $table->unsignedBigInteger('client_id');
            $table->enum('client_type', [ApplicantIndividual::class, Members::class])->default(Members::class);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_ip_address');
    }
}
