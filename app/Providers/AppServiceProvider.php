<?php

namespace App\Providers;

use Laravel\Passport\Console\ClientCommand;
use Laravel\Passport\Console\InstallCommand;
use Laravel\Passport\Console\KeysCommand;
use Laravel\Passport\Passport;
use App\Billings\Gateways\PaypalGateway;
use App\Billings\Gateways\StripeGateway;
use App\Billings\PaymentGatewayInterface;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PaymentGatewayInterface::class, function($app){
            $gateway = request()->gateway;
            if ($gateway === 'paypal')
                return new PaypalGateway();
            else if ($gateway === 'stripe')
                return new StripeGateway();
        });
    }
    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Passport::routes();
    
        /*ADD THIS LINES*/
        $this->commands([
            InstallCommand::class,
            ClientCommand::class,
            KeysCommand::class,
        ]);
    }
}
