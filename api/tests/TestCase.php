<?php

namespace Tests;

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
        return require __DIR__ . '/../bootstrap/app.php';
    }

    /**
     * Login
     *
     * @param  array  $data
     * @return string
     */
    public function login(array $data = []): string
    {
        if (empty($data)) {
            $data = ['email' => 'test@test.com', 'password' => '1234567Qa'];
        }

        return auth()->attempt($data);
    }

    /**
     * Login as Super admin
     *
     * @param  array  $data
     * @return string
     */
    public function loginAsSuperAdmin(): string
    {
        $data = ['email' => 'superadmin@test.com', 'password' => '1234567Qa'];

        return $this->login($data);
    }

    /**
     * Logout
     *
     * @param  string  $token
     * @return void
     */
    public function logout(string $token): void
    {
        auth()->logout(['token' => $token]);
    }
}
