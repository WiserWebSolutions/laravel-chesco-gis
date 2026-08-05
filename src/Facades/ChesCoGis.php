<?php

namespace WiserWebSolutions\ChesCoGis\Facades;

use Illuminate\Support\Facades\Facade;
use WiserWebSolutions\ChesCoGis\ChesCoGisManager;

/**
 * @method static \WiserWebSolutions\ChesCoGis\Parcels\ParcelQuery parcels()
 * @method static \WiserWebSolutions\ChesCoGis\Districts\DistrictQuery congressionalDistricts()
 * @method static \WiserWebSolutions\ChesCoGis\Districts\DistrictQuery stateSenateDistricts()
 * @method static \WiserWebSolutions\ChesCoGis\Districts\DistrictQuery stateHouseDistricts()
 * @method static \WiserWebSolutions\ChesCoGis\Bridges\BridgeQuery bridges()
 *
 * @see ChesCoGisManager
 */
class ChesCoGis extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'chesco-gis';
    }
}
