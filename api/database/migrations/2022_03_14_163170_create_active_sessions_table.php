<?php

use PhpClickHouseLaravel\Migration;

class CreateActiveSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        static::write('
            CREATE TABLE active_sessions (
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
        static::write('DROP TABLE active_sessions');
    }
}
