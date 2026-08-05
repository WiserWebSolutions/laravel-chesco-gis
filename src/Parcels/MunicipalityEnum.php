<?php

namespace WiserWebSolutions\ChesCoGis\Parcels;

/**
 * Chester County municipality codes (the parcel `MUNI` field).
 *
 * @see https://www.chesco.org/DocumentCenter/View/5366/muni_list_numberical_order
 */
enum MunicipalityEnum: string
{
    case WestChester = '1';
    case Malvern = '2';
    case KennettSquare = '3';
    case Avondale = '4';
    case WestGrove = '5';
    case Oxford = '6';
    case Atglen = '7';
    case Parkesburg = '8';
    case SouthCoatesville = '9';
    case Modena = '10';
    case Downingtown = '11';
    case HoneyBrookBorough = '12';
    case Elverson = '13';
    case SpringCity = '14';
    case Phoenixville = '15';
    case Coatesville = '16';
    case NorthCoventry = '17';
    case EastCoventry = '18';
    case Warwick = '19';
    case SouthCoventry = '20';
    case EastVincent = '21';
    case HoneyBrookTownship = '22';
    case WestNantmeal = '23';
    case EastNantmeal = '24';
    case WestVincent = '25';
    case EastPikeland = '26';
    case Schuylkill = '27';
    case WestCaln = '28';
    case WestBrandywine = '29';
    case EastBrandywine = '30';
    case Wallace = '31';
    case UpperUwchlan = '32';
    case Uwchlan = '33';
    case WestPikeland = '34';
    case Charlestown = '35';
    case WestSadsbury = '36';
    case Sadsbury = '37';
    case Valley = '38';
    case Caln = '39';
    case EastCaln = '40';
    case WestWhiteland = '41';
    case EastWhiteland = '42';
    case Tredyffrin = '43';
    case WestFallowfield = '44';
    case Highland = '45';
    case Londonderry = '46';
    case EastFallowfield = '47';
    case WestMarlborough = '48';
    case Newlin = '49';
    case WestBradford = '50';
    case EastBradford = '51';
    case WestGoshen = '52';
    case EastGoshen = '53';
    case Willistown = '54';
    case Easttown = '55';
    case LowerOxford = '56';
    case UpperOxford = '57';
    case Penn = '58';
    case LondonGrove = '59';
    case NewGarden = '60';
    case EastMarlborough = '61';
    case Kennett = '62';
    case Pocopson = '63';
    case Pennsbury = '64';
    case Birmingham = '65';
    case Thornbury = '66';
    case Westtown = '67';
    case WestNottingham = '68';
    case EastNottingham = '69';
    case Elk = '70';
    case NewLondon = '71';
    case Franklin = '72';
    case LondonBritain = '73';

    public function description(): string
    {
        return match ($this) {
            self::WestChester => 'West Chester',
            self::Malvern => 'Malvern',
            self::KennettSquare => 'Kennett Square',
            self::Avondale => 'Avondale',
            self::WestGrove => 'West Grove',
            self::Oxford => 'Oxford',
            self::Atglen => 'Atglen',
            self::Parkesburg => 'Parkesburg',
            self::SouthCoatesville => 'South Coatesville',
            self::Modena => 'Modena',
            self::Downingtown => 'Downingtown',
            self::HoneyBrookBorough => 'Honey Brook Borough',
            self::Elverson => 'Elverson',
            self::SpringCity => 'Spring City',
            self::Phoenixville => 'Phoenixville',
            self::Coatesville => 'Coatesville',
            self::NorthCoventry => 'North Coventry',
            self::EastCoventry => 'East Coventry',
            self::Warwick => 'Warwick',
            self::SouthCoventry => 'South Coventry',
            self::EastVincent => 'East Vincent',
            self::HoneyBrookTownship => 'Honey Brook Township',
            self::WestNantmeal => 'West Nantmeal',
            self::EastNantmeal => 'East Nantmeal',
            self::WestVincent => 'West Vincent',
            self::EastPikeland => 'East Pikeland',
            self::Schuylkill => 'Schuylkill',
            self::WestCaln => 'West Caln',
            self::WestBrandywine => 'West Brandywine',
            self::EastBrandywine => 'East Brandywine',
            self::Wallace => 'Wallace',
            self::UpperUwchlan => 'Upper Uwchlan',
            self::Uwchlan => 'Uwchlan',
            self::WestPikeland => 'West Pikeland',
            self::Charlestown => 'Charlestown',
            self::WestSadsbury => 'West Sadsbury',
            self::Sadsbury => 'Sadsbury',
            self::Valley => 'Valley',
            self::Caln => 'Caln',
            self::EastCaln => 'East Caln',
            self::WestWhiteland => 'West Whiteland',
            self::EastWhiteland => 'East Whiteland',
            self::Tredyffrin => 'Tredyffrin',
            self::WestFallowfield => 'West Fallowfield',
            self::Highland => 'Highland',
            self::Londonderry => 'Londonderry',
            self::EastFallowfield => 'East Fallowfield',
            self::WestMarlborough => 'West Marlborough',
            self::Newlin => 'Newlin',
            self::WestBradford => 'West Bradford',
            self::EastBradford => 'East Bradford',
            self::WestGoshen => 'West Goshen',
            self::EastGoshen => 'East Goshen',
            self::Willistown => 'Willistown',
            self::Easttown => 'Easttown',
            self::LowerOxford => 'Lower Oxford',
            self::UpperOxford => 'Upper Oxford',
            self::Penn => 'Penn',
            self::LondonGrove => 'London Grove',
            self::NewGarden => 'New Garden',
            self::EastMarlborough => 'East Marlborough',
            self::Kennett => 'Kennett',
            self::Pocopson => 'Pocopson',
            self::Pennsbury => 'Pennsbury',
            self::Birmingham => 'Birmingham',
            self::Thornbury => 'Thornbury',
            self::Westtown => 'Westtown',
            self::WestNottingham => 'West Nottingham',
            self::EastNottingham => 'East Nottingham',
            self::Elk => 'Elk',
            self::NewLondon => 'New London',
            self::Franklin => 'Franklin',
            self::LondonBritain => 'London Britain',
        };
    }

    /**
     * Every {@see SchoolDistrictEnum} this municipality falls within — the inverse of
     * {@see SchoolDistrictEnum::municipalities()}. Usually a single district, but a
     * handful of municipalities (e.g. East Marlborough) are split between two.
     *
     * @return array<int, SchoolDistrictEnum>
     */
    public function schoolDistricts(): array
    {
        return array_values(array_filter(
            SchoolDistrictEnum::cases(),
            fn (SchoolDistrictEnum $schoolDistrict) => in_array($this, $schoolDistrict->municipalities(), true),
        ));
    }
}
