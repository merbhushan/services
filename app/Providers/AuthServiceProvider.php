<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\setting;

class AuthServiceProvider extends ServiceProvider
{
    // Define object of setting
    private $objSetting;

    public function __construct(){
        $this->objSetting = new setting;
    }
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // Define Passport Rotes
        Passport::routes();
        // Set Token Expiration Time
        Passport::tokensExpireIn(now()->addDays((int)$this->objSetting->getSetting('TOKEN_EXPIRE_IN')->value));
        Passport::refreshTokensExpireIn(now()->addDays((int)$this->objSetting->getSetting('REFRESH_TOKEN_EXPIRE_IN')->value));
    }
}
