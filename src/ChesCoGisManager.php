<?php

namespace WiserWebSolutions\ChesCoGis;

use Illuminate\Contracts\Foundation\Application;
use WiserWebSolutions\ChesCoGis\Bridges\BridgeQuery;
use WiserWebSolutions\ChesCoGis\Districts\DistrictQuery;
use WiserWebSolutions\ChesCoGis\Parcels\ParcelQuery;

class ChesCoGisManager
{
    public function __construct(
        protected Application $app,
    ) {}

    public function parcels(): ParcelQuery
    {
        return new ParcelQuery($this->app->make(ArcGisClient::class));
    }

    public function congressionalDistricts(): DistrictQuery
    {
        return new DistrictQuery($this->app->make(ArcGisClient::class), 'congressional_districts');
    }

    public function stateSenateDistricts(): DistrictQuery
    {
        return new DistrictQuery($this->app->make(ArcGisClient::class), 'state_senate_districts');
    }

    public function stateHouseDistricts(): DistrictQuery
    {
        return new DistrictQuery($this->app->make(ArcGisClient::class), 'state_house_districts');
    }

    public function bridges(): BridgeQuery
    {
        return new BridgeQuery($this->app->make(ArcGisClient::class));
    }
}
