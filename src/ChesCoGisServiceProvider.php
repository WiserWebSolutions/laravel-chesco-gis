<?php

namespace WiserWebSolutions\ChesCoGis;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\ServiceProvider;

class ChesCoGisServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/chesco-gis.php', 'chesco-gis');

        $this->app->singleton(HttpFactory::class);

        $this->app->singleton(ArcGisClient::class);

        $this->app->singleton('chesco-gis', function ($app) {
            return new ChesCoGisManager($app);
        });

        $this->app->alias('chesco-gis', ChesCoGisManager::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/chesco-gis.php' => $this->app->configPath('chesco-gis.php'),
            ], 'chesco-gis-config');
        }
    }
}
