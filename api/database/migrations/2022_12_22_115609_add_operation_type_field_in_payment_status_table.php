<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddOperationTypeFieldInPaymentStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_status', function (Blueprint $table) {
            $table->string('operation_type', 255)->nullable();
        });
        DB::statement('ALTER TABLE payment_status ALTER COLUMN operation_type TYPE text[] USING ARRAY[operation_type]');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_status', function (Blueprint $table) {
            $table->dropColumn('operation_type');
        });
    }
}
