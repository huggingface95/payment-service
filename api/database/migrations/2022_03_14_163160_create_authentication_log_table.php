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
                id UInt32,
                company String,
                member String,
                group String,
                domain String,
                ip String,
                country String,
                city String,
                platform String,
                browser String,
                device_type String,
                model String,
                status String,
                info String,
                expired_at DateTime,
                created_at DateTime
            )
            ENGINE = MergeTree()
            ORDER BY (id)
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
