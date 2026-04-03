<?php

namespace App\Providers;

//use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Carbon\Carbon;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        $this->registerPolicies();

       // Passport::routes();

        Passport::tokensExpireIn(Carbon::now()->addDays(7));

        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));

        Passport::personalAccessTokensExpireIn(Carbon::now()->addDays(30));


    }
}
