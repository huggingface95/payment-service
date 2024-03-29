<?php

namespace Tests;

use App\Repositories\JWTRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use Nuwave\Lighthouse\Testing\ClearsSchemaCache;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequestsLumen;

abstract class TestCase extends BaseTestCase
{
    use MakesGraphQLRequestsLumen;
    use ClearsSchemaCache;

    public JWTRepository $repository;

    protected static $setUpHasRunOnce = false;

    protected function setUp(): void
    {
        parent::setUp();
        if (! static::$setUpHasRunOnce) {
            Artisan::call(
                'migrate:droptables'
            );
            Artisan::call(
                'migrate',
                ['--database' => 'pgsql_test']
            );
            Artisan::call(
                'db:seed',
                ['--database' => 'pgsql_test']
            );

            static::$setUpHasRunOnce = true;
        }
        $this->applyNotDeletedScopeToSoftDeleteModels();
    }

    protected function applyNotDeletedScopeToSoftDeleteModels(): void
    {
        $softDeleteModels = $this->getSoftDeleteModels();

        foreach ($softDeleteModels as $modelClass) {
            $modelClass::addGlobalScope('notDeleted', function (Builder $builder) {
                $builder->whereNull('deleted_at');
            });
        }
    }

    protected function getSoftDeleteModels(): array
    {
        $modelsPath = app_path('Models');
        $softDeleteModels = [];

        foreach (glob($modelsPath . '/*.php') as $modelFile) {
            $modelClass = 'App\Models\\' . basename($modelFile, '.php');
            $modelInstance = new $modelClass();

            if (method_exists($modelInstance, 'bootSoftDeletes')) {
                $softDeleteModels[] = $modelClass;
            }
        }

        return $softDeleteModels;
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

    /**
     * Login
     *
     * @param  array  $data
     * @return string
     */
    public function login(array $data = []): string
    {
        if (empty($data)) {
            $data = ['email' => 'test@test.com', 'password' => env('DEFAULT_PASSWORD', '1234567Qa')];
        }

        $key = $data['email'];
        if (Cache::store('redis')->has($key)) {
            return Cache::store('redis')->get($key);
        }

        $token = Http::accept('application/json')->post(env('AUTH_URL', 'http://go-auth:2491/auth/login'), $data);
        $accessToken = $token->json('access_token');

        Cache::store('redis')->put($key, $accessToken, env('JWT_TTL', 600));

        return $accessToken;
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
