<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DropTestTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:droptables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop all Tests Tables';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = 'SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname=\'public\'';
        $tables = array_column(DB::connection('pgsql_test')->select($query), 'tablename');

        foreach ($tables as $table) {
            DB::connection('pgsql_test')->statement('drop table '.$table.' cascade');
        }
        DB::connection('clickhouse_test')->statement('drop table if exists testdb.activity_log');
        DB::connection('clickhouse_test')->statement('drop table if exists testdb.active_sessions');
        DB::connection('clickhouse_test')->statement('drop table if exists testdb.authentication_log');
    }
}

