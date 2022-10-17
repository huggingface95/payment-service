<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateActiveSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection('clickhouse')->unprepared('
            CREATE TABLE active_sessions (
                id UUID,
                provider String,
                email String,
                company String,
                ip String,
                platform String,
                browser String,
                browser_version String,
                device_type String,
                model String,
                country String,
                city String,
                active Bool DEFAULT true,
                trusted Bool DEFAULT false,
                cookie Nullable(String),
                created_at DateTime DEFAULT now()
            )
            ENGINE = MergeTree()
            ORDER BY created_at
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection('clickhouse')->unprepared('DROP TABLE active_sessions');
    }
}
