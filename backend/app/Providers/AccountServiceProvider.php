<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Implementations\Account\JWT;
use App\Repositories\Account\AccountRepository;
use App\Contracts\AccountInterface;
use Illuminate\Http\Request;
    
class AccountServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->app->singleton(AccountInterface::class, function($app) {
            return new JWT(new AccountRepository());
        });
    }
}
