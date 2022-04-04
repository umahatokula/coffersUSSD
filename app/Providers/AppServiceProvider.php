<?php

namespace App\Providers;

use App\Repositories\CustomerRepostory;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CustomerRepostoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CustomerRepostoryInterface::class, CustomerRepostory::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
