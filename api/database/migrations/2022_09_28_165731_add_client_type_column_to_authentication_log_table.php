<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddClientTypeColumnToAuthenticationLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection('clickhouse')->unprepared('
            ALTER TABLE authentication_log ADD COLUMN client_type String AFTER email
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
            ALTER TABLE authentication_log DROP COLUMN client_type
        ');
    }
}
