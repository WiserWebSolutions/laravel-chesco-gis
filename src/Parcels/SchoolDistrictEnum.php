<?php

namespace WiserWebSolutions\ChesCoGis\Parcels;

/**
 * Chester County school district codes (the parcel `SCHDIST` field).
 *
 * @see https://www.chesco.org/DocumentCenter/View/5367
 */
enum SchoolDistrictEnum: string
{
    case AvonGrove = '01';
    case Coatesville = '02';
    case Downingtown = '03';
    case KennettConsolidated = '04';
    case Octorara = '05';
    case OwenJRoberts = '06';
    case Oxford = '07';
    case Phoenixville = '08';
    case TwinValley = '09';
    case UnionvilleChaddsFord = '10';
    case WestChester = '11';
    case GreatValley = '12';
    case TredyffrinEasttown = '13';
    case SpringFord = '14';

    public function description(): string
    {
        return match ($this) {
            self::AvonGrove => 'Avon Grove',
            self::Coatesville => 'Coatesville',
            self::Downingtown => 'Downingtown',
            self::KennettConsolidated => 'Kennett Consolidated',
            self::Octorara => 'Octorara',
            self::OwenJRoberts => 'Owen J. Roberts',
            self::Oxford => 'Oxford',
            self::Phoenixville => 'Phoenixville',
            self::TwinValley => 'Twin Valley',
            self::UnionvilleChaddsFord => 'Unionville-Chadds Ford',
            self::WestChester => 'West Chester',
            self::GreatValley => 'Great Valley',
            self::TredyffrinEasttown => 'Tredyffrin-Easttown',
            self::SpringFord => 'Spring-Ford',
        };
    }

    /**
     * Every {@see MunicipalityEnum} within this school district — the inverse of
     * {@see MunicipalityEnum::schoolDistricts()}. East Marlborough Township is
     * split between {@see self::KennettConsolidated} (independent portion only)
     * and {@see self::UnionvilleChaddsFord}, so it appears in both lists.
     *
     * @return array<int, MunicipalityEnum>
     */
    public function municipalities(): array
    {
        return match ($this) {
            self::AvonGrove => [
                MunicipalityEnum::Avondale,
                MunicipalityEnum::Franklin,
                MunicipalityEnum::LondonBritain,
                MunicipalityEnum::LondonGrove,
                MunicipalityEnum::NewLondon,
                MunicipalityEnum::Penn,
                MunicipalityEnum::WestGrove,
            ],
            self::Coatesville => [
                MunicipalityEnum::WestBrandywine,
                MunicipalityEnum::Caln,
                MunicipalityEnum::WestCaln,
                MunicipalityEnum::Coatesville,
                MunicipalityEnum::SouthCoatesville,
                MunicipalityEnum::EastFallowfield,
                MunicipalityEnum::Modena,
                MunicipalityEnum::Sadsbury,
                MunicipalityEnum::Valley,
            ],
            self::Downingtown => [
                MunicipalityEnum::WestBradford,
                MunicipalityEnum::EastBrandywine,
                MunicipalityEnum::EastCaln,
                MunicipalityEnum::Downingtown,
                MunicipalityEnum::WestPikeland,
                MunicipalityEnum::Uwchlan,
                MunicipalityEnum::UpperUwchlan,
                MunicipalityEnum::Wallace,
            ],
            self::KennettConsolidated => [
                MunicipalityEnum::KennettSquare,
                MunicipalityEnum::Kennett,
                MunicipalityEnum::EastMarlborough,
                MunicipalityEnum::NewGarden,
            ],
            self::Octorara => [
                MunicipalityEnum::Atglen,
                MunicipalityEnum::WestFallowfield,
                MunicipalityEnum::Highland,
                MunicipalityEnum::Londonderry,
                MunicipalityEnum::Parkesburg,
                MunicipalityEnum::WestSadsbury,
            ],
            self::OwenJRoberts => [
                MunicipalityEnum::EastCoventry,
                MunicipalityEnum::NorthCoventry,
                MunicipalityEnum::SouthCoventry,
                MunicipalityEnum::EastNantmeal,
                MunicipalityEnum::EastVincent,
                MunicipalityEnum::WestVincent,
                MunicipalityEnum::Warwick,
            ],
            self::Oxford => [
                MunicipalityEnum::Elk,
                MunicipalityEnum::EastNottingham,
                MunicipalityEnum::WestNottingham,
                MunicipalityEnum::Oxford,
                MunicipalityEnum::LowerOxford,
                MunicipalityEnum::UpperOxford,
            ],
            self::Phoenixville => [
                MunicipalityEnum::Phoenixville,
                MunicipalityEnum::EastPikeland,
                MunicipalityEnum::Schuylkill,
            ],
            self::TwinValley => [
                MunicipalityEnum::Elverson,
                MunicipalityEnum::HoneyBrookBorough,
                MunicipalityEnum::HoneyBrookTownship,
                MunicipalityEnum::WestNantmeal,
            ],
            self::UnionvilleChaddsFord => [
                MunicipalityEnum::Birmingham,
                MunicipalityEnum::EastMarlborough,
                MunicipalityEnum::WestMarlborough,
                MunicipalityEnum::Newlin,
                MunicipalityEnum::Pennsbury,
                MunicipalityEnum::Pocopson,
            ],
            self::WestChester => [
                MunicipalityEnum::EastBradford,
                MunicipalityEnum::EastGoshen,
                MunicipalityEnum::WestGoshen,
                MunicipalityEnum::Thornbury,
                MunicipalityEnum::Westtown,
                MunicipalityEnum::WestWhiteland,
                MunicipalityEnum::WestChester,
            ],
            self::GreatValley => [
                MunicipalityEnum::Charlestown,
                MunicipalityEnum::EastWhiteland,
                MunicipalityEnum::Willistown,
                MunicipalityEnum::Malvern,
            ],
            self::TredyffrinEasttown => [
                MunicipalityEnum::Easttown,
                MunicipalityEnum::Tredyffrin,
            ],
            self::SpringFord => [
                MunicipalityEnum::SpringCity,
            ],
        };
    }
}
