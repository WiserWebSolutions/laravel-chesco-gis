# Laravel ChesCo GIS

Fluent Laravel client for Chester County's ArcGIS REST FeatureServer datasets. Handles the generic FeatureServer query/pagination/error-handling mechanics once (`ArcGisClient`), so each dataset is just a layer config entry plus a small typed query builder.

## Datasets

- **Parcels** (`ChesCoGis::parcels()`) — Chester County parcels/owners layer (UPI, address, municipality, school district code, assessed value, owner, land use/class codes).
- **US Congressional Districts** (`ChesCoGis::congressionalDistricts()`) — district boundary + legislator.
- **State Senate Districts** (`ChesCoGis::stateSenateDistricts()`) — district boundary + legislator.
- **State House Districts** (`ChesCoGis::stateHouseDistricts()`) — district boundary + legislator.
- **Bridges** (`ChesCoGis::bridges()`) — PennDOT-sourced bridge inventory (location, span/owner info, condition ratings, point geometry).

Adding a new dataset (zoning, floodplains, municipal boundaries, ...) means: add a `layers.{name}` entry to `config/chesco-gis.php`, and add a `src/{Name}/{Name}Query.php` + `{Name}.php` DTO pair following the `Parcels` example.

## Usage

```php
use WiserWebSolutions\ChesCoGis\Facades\ChesCoGis;
use WiserWebSolutions\ChesCoGis\Parcels\MunicipalityEnum;
use WiserWebSolutions\ChesCoGis\Parcels\SchoolDistrictEnum;

ChesCoGis::parcels()
    ->whereMunicipality(MunicipalityEnum::Phoenixville, MunicipalityEnum::Malvern) // typed filters accept one or more enum values
    ->whereSchoolDistrict(SchoolDistrictEnum::Phoenixville)
    ->whereAssessedValueBetween(100000, 250000) // inclusive range filter
    ->where('OWN1 IS NOT NULL') // raw ArcGIS where clauses still work and AND-combine with everything else
    ->get(); // Collection<Parcel>, eagerly pages through the entire FeatureServer

ChesCoGis::parcels()
    ->whereLandUse(LandUseEnum::R_10) // also: ->wherePropertyClass(...), ->whereUpi(...), ->whereStreetDirection(...)
    ->each() // LazyCollection<Parcel>, pages through the FeatureServer as consumed
    ->each(function ($parcel) {
        // $parcel->upi, $parcel->schoolDistrict, $parcel->totalAssessment, $parcel->hash(), ...
        // $parcel->landUse is a WiserWebSolutions\ChesCoGis\Parcels\LandUseEnum enum (->description(), ->propertyClass())
        // $parcel->propertyClass is a WiserWebSolutions\ChesCoGis\Parcels\PropertyClassEnum enum (->description(), ->landUses())
        // $parcel->municipality is a WiserWebSolutions\ChesCoGis\Parcels\MunicipalityEnum enum (->description(), ->schoolDistricts())
        // $parcel->schoolDistrict is a WiserWebSolutions\ChesCoGis\Parcels\SchoolDistrictEnum enum (->description(), ->municipalities())
        // $parcel->streetDirection is a WiserWebSolutions\ChesCoGis\Parcels\StreetDirectionEnum enum (->description())
    });

ChesCoGis::congressionalDistricts() // also ::stateSenateDistricts(), ::stateHouseDistricts()
    ->whereDistrictNumber(6, 7) // also: ->whereLastName(...)
    ->each() // includes boundary geometry by default; call ->withoutGeometry() to skip it
    ->each(function ($district) {
        // $district->districtNumber, $district->representativeFirstName, $district->geometry, ...
    });

ChesCoGis::bridges()
    ->whereStructurallyDeficient() // also: ->whereFunctionallyObsolete(), ->whereBridgeId(...)
    ->whereSufficiencyRatingBetween(0, 50) // also: ->whereSufficiencyRatingBelow(50)
    ->whereDeckCondition(BridgeConditionEnum::Poor, BridgeConditionEnum::Serious) // also: ->whereSuperstructureCondition(...), ->whereSubstructureCondition(...), ->whereCulvertCondition(...)
    ->wherePostStatus(BridgePostStatusEnum::Posted) // also: ->whereOwnerCode(BridgeOwnerEnum::CountyHighwayAgency, ...)
    ->each() // includes point geometry by default; call ->withoutGeometry() to skip it
    ->each(function ($bridge) {
        // $bridge->bridgeId, $bridge->name, $bridge->sufficiencyRating, $bridge->geometry, ...
        // $bridge->deckCondition, $bridge->superstructureCondition, $bridge->substructureCondition, $bridge->culvertCondition are
        // WiserWebSolutions\ChesCoGis\Bridges\BridgeConditionEnum (->description(), ->rating())
        // $bridge->ownerCode is a WiserWebSolutions\ChesCoGis\Bridges\BridgeOwnerEnum (->description())
        // $bridge->postStatus is a WiserWebSolutions\ChesCoGis\Bridges\BridgePostStatusEnum (->description())
    });
```

Every query builder offers both `->get()` (eager `Collection`) and `->each()` (lazy, pages as consumed) — use `each()` for large datasets like parcels where you don't want the whole result set in memory at once.

Filters are chainable and AND-combined, similar to Laravel's own query builder: each `->whereX()` call (typed or raw `->where()`) adds a condition. The typed `IN`-style filters (`whereMunicipality()`, `whereSchoolDistrict()`, etc.) accept one or more enum values, translated to a properly-escaped ArcGIS `IN (...)` clause; the typed range filters (`whereAssessedValueBetween()`, `whereSufficiencyRatingBetween()`) translate to an inclusive `BETWEEN ... AND ...` clause. Reach for `->where('raw ArcGIS clause')` for anything the typed filters don't cover.

This package only fetches and types the ArcGIS data — persistence, sync-run tracking, filtering by school district, and notifications stay in the consuming app.

## Configuration

```bash
php artisan vendor:publish --tag=chesco-gis-config
```

Layer URLs/IDs are overridable per-layer via `.env` (see `config/chesco-gis.php`).

## Reference documents

The enums in `src/Parcels` (`LandUseEnum`, `PropertyClassEnum`, `MunicipalityEnum`, `SchoolDistrictEnum`) are transcribed from Chester County's own code-list PDFs. Copies are kept under [`resources/reference/`](resources/reference/) so the source is available if a code list needs re-verifying or updating:

- [`land-use-codes.pdf`](resources/reference/land-use-codes.pdf) — `LUC`/`CLASS` codes ([source](https://www.chesco.org/DocumentCenter/View/8673/Land_Use_Codes))
- [`municipality-codes.pdf`](resources/reference/municipality-codes.pdf) — `MUNI` codes ([source](https://www.chesco.org/DocumentCenter/View/5366/muni_list_numberical_order))
- [`school-district-codes.pdf`](resources/reference/school-district-codes.pdf) — `SCHDIST` codes, including which municipalities belong to each district ([source](https://www.chesco.org/DocumentCenter/View/5367))

`src/Parcels/StreetDirectionEnum.php` is the standard 8-point street pre-directional abbreviation set (N/S/E/W/NE/NW/SE/SW) for the `DIR` field — not Chester County-specific either.

`src/Bridges/BridgeConditionEnum.php` is the standard FHWA National Bridge Inventory (NBI) 0-9 condition rating scale (plus `N` for not applicable), used for the `DECK_CONDITION`, `SUPERSTRUCTURE_CONDITION`, `SUBSTRUCTURE_CONDITION`, and `CULVERT_CONDITION` fields — this one is a fixed federal standard rather than a Chester County-specific list, so there's no source PDF to keep a copy of.

`src/Bridges/BridgeOwnerEnum.php` is the FHWA NBI Item 22 "Owner" code list for the `OWNER_CODE` field. That field is inconsistently entered upstream — some rows have the numeric code, others the spelled-out description, occasionally with the wrong value entirely (e.g. a name that doesn't belong there) — so `Bridge::fromFeature()` uses `BridgeOwnerEnum::fromRaw()` rather than the built-in `tryFrom()` to normalize all the forms actually seen in the data, falling back to `null` for anything unrecognized.

`src/Bridges/BridgePostStatusEnum.php` covers the `POST_STATUS` field (`OPEN`/`CLOSED`/`POSTED`).
