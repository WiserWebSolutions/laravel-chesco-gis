<?php

namespace WiserWebSolutions\ChesCoGis\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use WiserWebSolutions\ChesCoGis\ChesCoGisServiceProvider;
use WiserWebSolutions\ChesCoGis\Facades\ChesCoGis;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            ChesCoGisServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return ['ChesCoGis' => ChesCoGis::class];
    }

    protected function defineEnvironment($app): void
    {
        $config = $app['config'];
        $config->set('chesco-gis.layers.parcels', [
            'url' => 'https://example.test/arcgis/rest/services/Parcels_owners/FeatureServer',
            'layer_id' => 0,
        ]);
        $config->set('cache.default', 'array');
    }
}
