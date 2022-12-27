<?php

namespace Tests;

use App\DTO\Auth\Credentials;
use App\DTO\TransformerDTO;
use App\Repositories\JWTRepository;
use App\Services\AuthService;
use App\Services\JwtService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use Nuwave\Lighthouse\Testing\ClearsSchemaCache;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequestsLumen;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Token;
use App\Repositories\Interfaces\JWTRepositoryInterface;
use Laravel\Passport\ClientRepository;

abstract class TestCase extends BaseTestCase
{
    use MakesGraphQLRequestsLumen,
        ClearsSchemaCache;

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

        $token = Http::accept('application/json')->post('http://172.16.0.3:2491/auth/login', $data);
        //$user = Http::accept('application/json')->withHeaders(['Authorization' => 'Bearer '. $token])->post('http://172.16.0.3:2491/auth/me');
        $client = (new ClientRepository())->createPasswordGrantClient(2, 'Docudots', 'http://localhost', 'member');
        $client->update(['personal_access_client' => true]);
        $client->update(['password_client' => false]);
        $repository = new JWTRepository($client);
        $jwtService = new JwtService($repository);
        $credentials = $jwtService->parseJWT($token);
        $credentialsDto = TransformerDTO::transform(Credentials::class, $credentials);

        return $credentialsDto->model;
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
