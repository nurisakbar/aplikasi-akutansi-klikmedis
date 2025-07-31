<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

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
        // Share user data to all views
        View::composer('*', function ($view) {
            $view->with('currentUser', Auth::user());
        });

        // Configure AdminLTE user menu
        config([
            'adminlte.usermenu_enabled' => true,
            'adminlte.usermenu_header' => true,
            'adminlte.usermenu_header_class' => 'bg-primary',
            'adminlte.usermenu_image' => false,
            'adminlte.usermenu_desc' => true,
            'adminlte.usermenu_profile_url' => true,
            'adminlte.usermenu_logout' => true,
            'adminlte.usermenu_logout_method' => 'POST',
            'adminlte.usermenu_logout_btn_text' => 'Logout',
            'adminlte.usermenu_logout_btn_icon' => 'fas fa-sign-out-alt',
        ]);
    }
}
