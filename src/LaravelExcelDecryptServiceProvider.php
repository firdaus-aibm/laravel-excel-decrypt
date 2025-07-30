<?php

namespace FirdausAibm\LaravelExcelDecrypt;

use Illuminate\Support\ServiceProvider;

class LaravelExcelDecryptServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ExcelDecryptionService::class, function ($app) {
            return new ExcelDecryptionService();
        });

        $this->app->alias(ExcelDecryptionService::class, 'excel-decrypt');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish config file
        $this->publishes([
            __DIR__ . '/../config/excel-decrypt.php' => config_path('excel-decrypt.php'),
        ], 'excel-decrypt-config');

        // Load config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/excel-decrypt.php', 'excel-decrypt'
        );
    }
} 