<?php

use PhpClickHouseLaravel\Migration;

class CreateActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        static::write('
            CREATE TABLE activity_log (
                id UInt32,
                company String,
                member String,
                group String,
                domain String,
                description String,
                changes String,
                field_one String,
                field_two Int32,
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
        static::write('DROP TABLE activity_log');
    }
}
