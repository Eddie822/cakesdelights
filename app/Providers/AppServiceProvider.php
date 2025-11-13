<?php

namespace App\Providers;

use App\Http\Middleware\AdminMiddleware;
use App\Models\Cover;
use App\Observers\CoverObserver;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::aliasMiddleware('admin', AdminMiddleware::class);
        Cover::observe(CoverObserver::class);
        // Cart::setGlobalTax(0);
    }
}
