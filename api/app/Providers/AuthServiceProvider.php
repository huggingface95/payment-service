<?php

namespace App\Providers;

use App\DTO\Auth\Credentials;
use App\DTO\TransformerDTO;
use App\Services\Jwt\Guards\JwtGuard;
use App\Services\JwtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot(JwtService $jwtService)
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('go-auth', function (Request $request) use ($jwtService) {
            $token = $request->bearerToken();
            try {
                $credentials = $jwtService->parseJWT($token);
                $credentialsDto = TransformerDTO::transform(Credentials::class, $credentials);

                return $credentialsDto->model;
            } catch (\Throwable $e) {
                Log::log('error', $e->getMessage());

                return null;
            }
        });

        //to make it work Auth::guard('api_client')
        Auth::extend('go-auth', function (Application $app, $name, array $config) use ($jwtService) {
            $token = $app->get('request')->bearerToken();
            try {
                $credentials = $jwtService->parseJWT($token);
                $credentialsDto = TransformerDTO::transform(Credentials::class, $credentials);
                return new JwtGuard(Auth::createUserProvider($credentialsDto->type), $credentialsDto);
            } catch (\Throwable $e) {
                Log::log('error', $e->getMessage());
                $credentialsDto = TransformerDTO::transform(Credentials::class, (object) []);

                return new JwtGuard(Auth::createUserProvider($config['provider']), $credentialsDto);
            }
        });
//        Passport::personalAccessTokensExpireIn(Carbon::now()->addDays(env('PERSONAL_ACCESS_TOKEN_TTL', 365)));
    }
}
