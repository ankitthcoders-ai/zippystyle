<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        view()->composer('*', function ($view) {
            $verticalMenuJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
            $verticalMenuData = json_decode($verticalMenuJson);

            if (Auth::check() && Auth::user()->role !== 'admin') {
                $verticalMenuData->menu = array_values(array_filter(
                    $verticalMenuData->menu,
                    fn ($item) => empty($item->adminOnly)
                ));
            }

            $view->with('menuData', [$verticalMenuData]);
        });
    }
}
