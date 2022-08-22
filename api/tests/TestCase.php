<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use Nuwave\Lighthouse\Testing\ClearsSchemaCache;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequestsLumen;

abstract class TestCase extends BaseTestCase
{
    use MakesGraphQLRequestsLumen,
    ClearsSchemaCache;

    protected static $setUpHasRunOnce = false;

    protected function setUp(): void
    {
        parent::setUp();
        if (! static::$setUpHasRunOnce) {
            Artisan::call(
                'migrate:droptables'
            );
            Artisan::call(
                'migrate', ['--database' => 'pgsql_test']
            );
            Artisan::call(
                'db:seed', ['--database' => 'pgsql_test']
            );
            DB::connection('pgsql_test')->statement("alter table members add column fullname varchar(255) GENERATED ALWAYS AS (first_name || ' '|| last_name) STORED");
            DB::connection('pgsql_test')->statement("alter table applicant_individual add column fullname varchar(255) GENERATED ALWAYS AS (first_name || ' '|| last_name) STORED");
            static::$setUpHasRunOnce = true;
        }
    }

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }
}
