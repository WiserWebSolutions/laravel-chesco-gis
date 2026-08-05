<?php

namespace WiserWebSolutions\ChesCoGis\Bridges;

/**
 * FHWA National Bridge Inventory (NBI) Item 22 "Owner" code list, used for the
 * `OWNER_CODE` field. The raw data mixes zero-padded codes ("01"), unpadded
 * codes ("1"), and already-spelled-out descriptions ("STATE HIGHWAY AGENCY")
 * depending on how each record was entered — use {@see self::fromRaw()} rather
 * than the built-in `tryFrom()` to handle all three forms.
 *
 * @see https://www.fhwa.dot.gov/bridge/mtguide.pdf
 */
enum BridgeOwnerEnum: string
{
    case StateHighwayAgency = '01';
    case CountyHighwayAgency = '02';
    case TownOrTownshipHighwayAgency = '03';
    case CityOrMunicipalHighwayAgency = '04';
    case StateParkForestOrReservationAgency = '11';
    case LocalParkForestOrReservationAgency = '12';
    case OtherStateAgencies = '21';
    case OtherLocalAgencies = '25';
    case Private = '26';
    case Railroad = '27';
    case StateTollAuthority = '31';
    case LocalTollAuthority = '32';
    case OtherFederalAgencies = '60';
    case IndianTribalGovernment = '61';
    case BureauOfIndianAffairs = '62';
    case BureauOfFishAndWildlife = '63';
    case UsForestService = '64';
    case NationalParkService = '66';
    case TennesseeValleyAuthority = '67';
    case BureauOfLandManagement = '68';
    case BureauOfReclamation = '69';
    case CorpsOfEngineersCivil = '70';
    case CorpsOfEngineersMilitary = '71';
    case AirForce = '72';
    case NavyMarines = '73';
    case Army = '74';
    case Nasa = '75';
    case MetropolitanWashingtonAirportsService = '76';
    case Unknown = '80';

    public function description(): string
    {
        return match ($this) {
            self::StateHighwayAgency => 'State Highway Agency',
            self::CountyHighwayAgency => 'County Highway Agency',
            self::TownOrTownshipHighwayAgency => 'Town or Township Highway Agency',
            self::CityOrMunicipalHighwayAgency => 'City or Municipal Highway Agency',
            self::StateParkForestOrReservationAgency => 'State Park, Forest, or Reservation Agency',
            self::LocalParkForestOrReservationAgency => 'Local Park, Forest, or Reservation Agency',
            self::OtherStateAgencies => 'Other State Agencies',
            self::OtherLocalAgencies => 'Other Local Agencies',
            self::Private => 'Private (other than railroad)',
            self::Railroad => 'Railroad',
            self::StateTollAuthority => 'State Toll Authority',
            self::LocalTollAuthority => 'Local Toll Authority',
            self::OtherFederalAgencies => 'Other Federal Agencies',
            self::IndianTribalGovernment => 'Indian Tribal Government',
            self::BureauOfIndianAffairs => 'Bureau of Indian Affairs',
            self::BureauOfFishAndWildlife => 'Bureau of Fish and Wildlife',
            self::UsForestService => 'U.S. Forest Service',
            self::NationalParkService => 'National Park Service',
            self::TennesseeValleyAuthority => 'Tennessee Valley Authority',
            self::BureauOfLandManagement => 'Bureau of Land Management',
            self::BureauOfReclamation => 'Bureau of Reclamation',
            self::CorpsOfEngineersCivil => 'Corps of Engineers (Civil)',
            self::CorpsOfEngineersMilitary => 'Corps of Engineers (Military)',
            self::AirForce => 'Air Force',
            self::NavyMarines => 'Navy/Marines',
            self::Army => 'Army',
            self::Nasa => 'NASA',
            self::MetropolitanWashingtonAirportsService => 'Metropolitan Washington Airports Service',
            self::Unknown => 'Unknown',
        };
    }

    /**
     * Resolve a raw `OWNER_CODE` value, which may be a zero-padded code ("01"), an
     * unpadded code ("1"), or an already-spelled-out description ("STATE HIGHWAY
     * AGENCY"). Returns null for anything that doesn't match (blank, or bad data
     * like a stray name accidentally entered in the field).
     */
    public static function fromRaw(string $raw): ?self
    {
        $raw = trim($raw);

        if ($raw === '') {
            return null;
        }

        if (ctype_digit($raw)) {
            return self::tryFrom(str_pad($raw, 2, '0', STR_PAD_LEFT));
        }

        // A handful of records have the description entered in shorthand rather
        // than matching the official NBI wording — recognize those explicitly.
        $shorthand = match (strtoupper($raw)) {
            'TOWN OR TOWNSHIP AGENCY' => self::TownOrTownshipHighwayAgency,
            'CITY, MUNICIPAL, HIGHWAY AGENCY, OR BOROUGH', 'CITY OR MUNICIPAL AGENCY' => self::CityOrMunicipalHighwayAgency,
            default => null,
        };

        if ($shorthand !== null) {
            return $shorthand;
        }

        foreach (self::cases() as $case) {
            if (strcasecmp($case->description(), $raw) === 0) {
                return $case;
            }
        }

        return null;
    }
}
