<?php

namespace App\Providers;

use App\View\Composers\ClientShopComposer;
use Illuminate\Support\Facades\View;
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
        View::composer([
            'client.categories.index',
            'client.subcategories.index',
            'client.products.index',
            'client.products.show',
            'client.checkout.show',
        ], ClientShopComposer::class);
    }
}
