<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Define gates for roles
        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('guru', function ($user) {
            return $user->isGuru() || $user->isAdmin();
        });

        Gate::define('siswa', function ($user) {
            return $user->isSiswa();
        });
    }
}