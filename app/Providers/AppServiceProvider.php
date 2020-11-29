<?php

namespace App\Providers;

use App\Support\Ploi;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public function register()
    {
        $this->loadConfigurationFile();

        $this->app->singleton(Ploi::class, function () {
            return new Ploi(config('ploi.token'));
        });
    }

    protected function loadConfigurationFile()
    {
        $builtInConfig = config('ploi');

        $configFile = implode(DIRECTORY_SEPARATOR, [
            $_SERVER['HOME'] ?? $_SERVER['USERPROFILE'] ?? __DIR__,
            '.ploi',
            'config.php',
        ]);

        if (file_exists($configFile)) {
            $globalConfig = require $configFile;
            config()->set('ploi', array_merge($builtInConfig, $globalConfig));
        }
    }
}
