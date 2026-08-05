<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use WiserWebSolutions\ChesCoGis\Exceptions\ArcGisQueryException;
use WiserWebSolutions\ChesCoGis\Facades\ChesCoGis;
use WiserWebSolutions\ChesCoGis\Parcels\LandUseEnum;
use WiserWebSolutions\ChesCoGis\Parcels\MunicipalityEnum;
use WiserWebSolutions\ChesCoGis\Parcels\PropertyClassEnum;
use WiserWebSolutions\ChesCoGis\Parcels\SchoolDistrictEnum;

it('pages through the FeatureServer until exceededTransferLimit is false', function () {
    Http::fake([
        '*resultOffset=0*' => Http::response([
            'features' => [
                ['attributes' => ['UPI' => '1', 'OBJECTID' => 1]],
            ],
            'exceededTransferLimit' => true,
        ]),
        '*resultOffset=1*' => Http::response([
            'features' => [
                ['attributes' => ['UPI' => '2', 'OBJECTID' => 2]],
            ],
            'exceededTransferLimit' => false,
        ]),
    ]);

    $upis = ChesCoGis::parcels()
        ->each()
        ->map(fn ($parcel) => $parcel->upi)
        ->all();

    expect($upis)->toBe(['1', '2']);

    Http::assertSent(fn ($request) => str_contains((string) $request->url(), 'where=1%3D1'));
});

it('applies a custom where clause', function () {
    Http::fake([
        '*' => Http::response([
            'features' => [
                ['attributes' => ['UPI' => '1', 'OBJECTID' => 1]],
            ],
            'exceededTransferLimit' => false,
        ]),
    ]);

    ChesCoGis::parcels()->where("MUNI = '15'")->each()->all();

    Http::assertSent(fn ($request) => str_contains(urldecode((string) $request->url()), "where=MUNI = '15'"));
});

it('throws when the FeatureServer returns an ArcGIS error payload', function () {
    Http::fake([
        '*' => Http::response(['error' => ['message' => 'Invalid where clause']]),
    ]);

    ChesCoGis::parcels()->each()->all();
})->throws(ArcGisQueryException::class, 'Invalid where clause');

it('combines multiple where() calls with AND', function () {
    Http::fake(['*' => Http::response(['features' => [], 'exceededTransferLimit' => false])]);

    ChesCoGis::parcels()->where("MUNI = '15'")->where("TOT_ASSESS > 100000")->each()->all();

    Http::assertSent(fn ($request) => str_contains(
        urldecode((string) $request->url()),
        "where=MUNI = '15' AND TOT_ASSESS > 100000",
    ));
});

it('filters by one or more municipalities using MunicipalityEnum', function () {
    Http::fake(['*' => Http::response(['features' => [], 'exceededTransferLimit' => false])]);

    ChesCoGis::parcels()->whereMunicipality(MunicipalityEnum::Phoenixville, MunicipalityEnum::Malvern)->each()->all();

    Http::assertSent(fn ($request) => str_contains(urldecode((string) $request->url()), 'where=MUNI IN (15, 2)'));
});

it('filters by one or more school districts using SchoolDistrictEnum', function () {
    Http::fake(['*' => Http::response(['features' => [], 'exceededTransferLimit' => false])]);

    ChesCoGis::parcels()->whereSchoolDistrict(SchoolDistrictEnum::Phoenixville)->each()->all();

    Http::assertSent(fn ($request) => str_contains(urldecode((string) $request->url()), "where=SCHDIST IN ('08')"));
});

it('filters by one or more land uses using LandUseEnum', function () {
    Http::fake(['*' => Http::response(['features' => [], 'exceededTransferLimit' => false])]);

    ChesCoGis::parcels()->whereLandUse(LandUseEnum::R_10, LandUseEnum::R_20)->each()->all();

    Http::assertSent(fn ($request) => str_contains(urldecode((string) $request->url()), "where=LUC IN ('R-10', 'R-20')"));
});

it('filters by one or more property classes using PropertyClassEnum', function () {
    Http::fake(['*' => Http::response(['features' => [], 'exceededTransferLimit' => false])]);

    ChesCoGis::parcels()->wherePropertyClass(PropertyClassEnum::Residential)->each()->all();

    Http::assertSent(fn ($request) => str_contains(urldecode((string) $request->url()), "where=CLASS IN ('R')"));
});

it('filters by one or more UPIs', function () {
    Http::fake(['*' => Http::response(['features' => [], 'exceededTransferLimit' => false])]);

    ChesCoGis::parcels()->whereUpi('27-5-123', "O'Brien-1")->each()->all();

    Http::assertSent(fn ($request) => str_contains(
        urldecode((string) $request->url()),
        "where=UPI IN ('27-5-123', 'O''Brien-1')",
    ));
});

it('throws when a where-in filter is given no values', function () {
    ChesCoGis::parcels()->whereUpi();
})->throws(InvalidArgumentException::class);

it('filters by an assessed value range', function () {
    Http::fake(['*' => Http::response(['features' => [], 'exceededTransferLimit' => false])]);

    ChesCoGis::parcels()->whereAssessedValueBetween(100000, 250000)->each()->all();

    Http::assertSent(fn ($request) => str_contains(
        urldecode((string) $request->url()),
        'where=TOT_ASSESS BETWEEN 100000 AND 250000',
    ));
});

it('get() eagerly pages through the FeatureServer into a Collection', function () {
    Http::fake([
        '*resultOffset=0*' => Http::response([
            'features' => [
                ['attributes' => ['UPI' => '1', 'OBJECTID' => 1]],
            ],
            'exceededTransferLimit' => true,
        ]),
        '*resultOffset=1*' => Http::response([
            'features' => [
                ['attributes' => ['UPI' => '2', 'OBJECTID' => 2]],
            ],
            'exceededTransferLimit' => false,
        ]),
    ]);

    $parcels = ChesCoGis::parcels()->get();

    expect($parcels)->toBeInstanceOf(Collection::class)
        ->and($parcels->map(fn ($parcel) => $parcel->upi)->all())->toBe(['1', '2']);
});
