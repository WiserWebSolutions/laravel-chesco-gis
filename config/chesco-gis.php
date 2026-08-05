<?php

return [

    /*
    |--------------------------------------------------------------------------
    | HTTP behavior
    |--------------------------------------------------------------------------
    |
    | Applied to every FeatureServer request made by the package.
    |
    */

    'timeout' => (int) env('CHESCO_GIS_TIMEOUT', 120),

    'retry_times' => (int) env('CHESCO_GIS_RETRY_TIMES', 3),

    'retry_sleep_ms' => (int) env('CHESCO_GIS_RETRY_SLEEP_MS', 1000),

    'page_size' => (int) env('CHESCO_GIS_PAGE_SIZE', 2000),

    /*
    |--------------------------------------------------------------------------
    | Layers
    |--------------------------------------------------------------------------
    |
    | Every Chester County ArcGIS FeatureServer layer this package knows how
    | to query. Add an entry here (and a matching query class under src/)
    | when wiring up a new dataset — url/layer_id are the only per-layer
    | config a consuming app should need to override via .env.
    |
    */

    'layers' => [

        'parcels' => [
            'url' => env('CHESCO_GIS_PARCELS_URL', 'https://services.arcgis.com/G4S1dGvn7PIgYd6Y/ArcGIS/rest/services/Parcels_owners/FeatureServer'),
            'layer_id' => (int) env('CHESCO_GIS_PARCELS_LAYER_ID', 0),
        ],

        'congressional_districts' => [
            'url' => env('CHESCO_GIS_CONGRESSIONAL_DISTRICTS_URL', 'https://services.arcgis.com/G4S1dGvn7PIgYd6Y/ArcGIS/rest/services/US_Congressional_Districts/FeatureServer'),
            'layer_id' => (int) env('CHESCO_GIS_CONGRESSIONAL_DISTRICTS_LAYER_ID', 0),
        ],

        'state_senate_districts' => [
            'url' => env('CHESCO_GIS_STATE_SENATE_DISTRICTS_URL', 'https://services.arcgis.com/G4S1dGvn7PIgYd6Y/ArcGIS/rest/services/State_Senate_Districts/FeatureServer'),
            'layer_id' => (int) env('CHESCO_GIS_STATE_SENATE_DISTRICTS_LAYER_ID', 0),
        ],

        'state_house_districts' => [
            'url' => env('CHESCO_GIS_STATE_HOUSE_DISTRICTS_URL', 'https://services.arcgis.com/G4S1dGvn7PIgYd6Y/ArcGIS/rest/services/State_House_Districts/FeatureServer'),
            'layer_id' => (int) env('CHESCO_GIS_STATE_HOUSE_DISTRICTS_LAYER_ID', 0),
        ],

        'bridges' => [
            'url' => env('CHESCO_GIS_BRIDGES_URL', 'https://services.arcgis.com/G4S1dGvn7PIgYd6Y/ArcGIS/rest/services/Bridges/FeatureServer'),
            'layer_id' => (int) env('CHESCO_GIS_BRIDGES_LAYER_ID', 0),
        ],

    ],

];
