<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeOperationTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('operation_type', function (Blueprint $table) {
            $table->dropColumn('transfer_type');
            $table->unsignedInteger('transfer_type_id')->nullable();

            $table->foreign('transfer_type_id')->references('id')->on('transfer_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operation_type', function (Blueprint $table) {
            $table->dropForeign(['transfer_type_id']);

            $table->dropColumn('transfer_type_id');
            $table->string('transfer_type', 255)->nullable();
        });

        DB::statement('ALTER TABLE operation_type ALTER COLUMN transfer_type TYPE text[] USING ARRAY[transfer_type]');
    }
}
