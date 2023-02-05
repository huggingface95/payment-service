<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogTableTestDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection('clickhouse_test')->statement('drop table if exists testdb.activity_log');

        DB::connection('clickhouse_test')->unprepared('
            CREATE TABLE activity_log (
                id UUID,
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
        DB::connection('clickhouse_test')->unprepared('DROP TABLE activity_log');
    }
}
