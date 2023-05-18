<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddExpiredAtColumnToActiveSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection('clickhouse')->unprepared('ALTER TABLE active_sessions ADD COLUMN expired_at Nullable(DateTime)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection('clickhouse')->unprepared('ALTER TABLE active_sessions DROP COLUMN expired_at');
    }
}
