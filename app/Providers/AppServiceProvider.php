<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;

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
        // Self-healing: create standard storage subfolders if they are missing
        $dirs = [
            storage_path('app/public'),
            storage_path('app/public/deeds'),
            storage_path('app/public/letters'),
            storage_path('app/public/client_documents'),
            storage_path('framework/cache'),
            storage_path('framework/cache/data'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            storage_path('logs'),
        ];

        Paginator::useBootstrapFive();

        foreach ($dirs as $dir) {
            if (!file_exists($dir)) {
                try {
                    mkdir($dir, 0755, true);
                } catch (\Exception $e) {
                    // Ignore
                }
            }
        }

        // Self-healing symlink creation
        if (!file_exists(public_path('storage'))) {
            try {
                \Illuminate\Support\Facades\Artisan::call('storage:link');
            } catch (\Exception $e) {
                // Fail silently if symlink creation is not permitted by host OS
            }
        }
    }
}
