<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAuthenticationLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection('clickhouse')->unprepared('
            CREATE TABLE authentication_log (
                id UUID,
                provider String,
                email String,
                company String,
                domain String,
                ip String,
                country String,
                city String,
                status String,
                info String,
                platform String,
                browser String,
                browser_version String,
                device_type String,
                model String,
                expired_at Nullable(DateTime),
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
        DB::connection('clickhouse')->unprepared('DROP TABLE authentication_log');
    }
}
