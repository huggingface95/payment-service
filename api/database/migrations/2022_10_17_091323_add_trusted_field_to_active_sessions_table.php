<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddTrustedFieldToActiveSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection('clickhouse')->unprepared('
            ALTER TABLE active_sessions ADD COLUMN trusted Bool DEFAULT false AFTER model
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        DB::connection('clickhouse')->unprepared('
            ALTER TABLE active_sessions DROP COLUMN trusted
        ');
    }
}
