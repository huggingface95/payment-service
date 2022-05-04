<?php

namespace App\Providers;

use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\Members;
use App\Models\Payments;
use App\Models\User;
use App\Policies\BasePolicy;
use App\Policies\PaymentPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
    public function boot()
    {
        $this->usePolicies();
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->input('api_token')) {
                return User::where('api_token', $request->input('api_token'))->first();
            }
            return null;
        });
    }


    private function usePolicies()
    {
        Gate::policy(Payments::class, PaymentPolicy::class);
        Gate::policy(ApplicantIndividual::class, BasePolicy::class);
        Gate::policy(ApplicantCompany::class, BasePolicy::class);
        Gate::policy(Members::class, BasePolicy::class);
    }
}
