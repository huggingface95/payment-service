<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateClientIpAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_ip_address', function (Blueprint $table) {
            $table->dropColumn('client_type');
        });

        Schema::table('client_ip_address', function (Blueprint $table) {
            $table->enum('client_type', [class_basename(ApplicantIndividual::class), class_basename(Members::class)])->default(class_basename(Members::class));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_ip_address', function (Blueprint $table) {
            $table->dropColumn('client_type');
        });

        Schema::table('client_ip_address', function (Blueprint $table) {
            $table->enum('client_type', [ApplicantIndividual::class, Members::class])->default(Members::class);
        });
    }
}
