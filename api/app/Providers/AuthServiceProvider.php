<?php

namespace App\Providers;

use App\Models\ApplicantIndividual;
use App\Models\Members;
use App\Services\JwtService;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;

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
                return $credentials->prv == 'members' ? Members::find($credentials->jti) : ApplicantIndividual::find($credentials->jti);
            } catch (\Throwable $e) {
                dd($e);
                return null;
            }
        });

//        Passport::personalAccessTokensExpireIn(Carbon::now()->addDays(env('PERSONAL_ACCESS_TOKEN_TTL', 365)));
    }
}
