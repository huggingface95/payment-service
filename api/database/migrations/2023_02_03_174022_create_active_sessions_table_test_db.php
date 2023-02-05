<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateActiveSessionsTableTestDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection('clickhouse_test')->statement('drop table if exists testdb.active_sessions');

        DB::connection('clickhouse_test')->unprepared('
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
                lang String,
                model String,
                country String,
                city String,
                active Bool DEFAULT true,
                trusted Bool DEFAULT false,
                code Nullable(String),
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
        DB::connection('clickhouse_test')->unprepared('DROP TABLE active_sessions');
    }
}
