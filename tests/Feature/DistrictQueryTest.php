<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use WiserWebSolutions\ChesCoGis\Exceptions\ArcGisQueryException;
use WiserWebSolutions\ChesCoGis\Facades\ChesCoGis;

it('pages through the FeatureServer and requests geometry by default', function () {
    Http::fake([
        '*resultOffset=0*' => Http::response([
            'features' => [
                ['attributes' => ['LEG_DISTRICT' => '6', 'OBJECTID' => 1]],
            ],
            'exceededTransferLimit' => true,
        ]),
        '*resultOffset=1*' => Http::response([
            'features' => [
                ['attributes' => ['LEG_DISTRICT' => '7', 'OBJECTID' => 2]],
            ],
            'exceededTransferLimit' => false,
        ]),
    ]);

    $districtNumbers = ChesCoGis::congressionalDistricts()
        ->each()
        ->map(fn ($district) => $district->districtNumber)
        ->all();

    expect($districtNumbers)->toBe(['6', '7']);

    Http::assertSent(fn ($request) => str_contains((string) $request->url(), 'returnGeometry=true'));
});

it('skips geometry when withoutGeometry is called', function () {
    Http::fake([
        '*' => Http::response([
            'features' => [
                ['attributes' => ['LEG_DISTRICT' => '6', 'OBJECTID' => 1]],
            ],
            'exceededTransferLimit' => false,
        ]),
    ]);

    ChesCoGis::stateSenateDistricts()->withoutGeometry()->each()->all();

    Http::assertSent(fn ($request) => str_contains((string) $request->url(), 'returnGeometry=false'));
});

it('applies a custom where clause', function () {
    Http::fake([
        '*' => Http::response([
            'features' => [
                ['attributes' => ['LEG_DISTRICT' => '6', 'OBJECTID' => 1]],
            ],
            'exceededTransferLimit' => false,
        ]),
    ]);

    ChesCoGis::stateHouseDistricts()->where("LEG_DISTRICT = '6'")->each()->all();

    Http::assertSent(fn ($request) => str_contains(urldecode((string) $request->url()), "where=LEG_DISTRICT = '6'"));
});

it('throws when the FeatureServer returns an ArcGIS error payload', function () {
    Http::fake([
        '*' => Http::response(['error' => ['message' => 'Invalid where clause']]),
    ]);

    ChesCoGis::congressionalDistricts()->each()->all();
})->throws(ArcGisQueryException::class, 'Invalid where clause');

it('filters by one or more district numbers', function () {
    Http::fake(['*' => Http::response(['features' => [], 'exceededTransferLimit' => false])]);

    ChesCoGis::congressionalDistricts()->whereDistrictNumber(6, 7)->each()->all();

    Http::assertSent(fn ($request) => str_contains(urldecode((string) $request->url()), "where=LEG_DISTRICT IN ('6', '7')"));
});

it('filters by one or more representative last names', function () {
    Http::fake(['*' => Http::response(['features' => [], 'exceededTransferLimit' => false])]);

    ChesCoGis::stateSenateDistricts()->whereLastName('Doe')->each()->all();

    Http::assertSent(fn ($request) => str_contains(urldecode((string) $request->url()), "where=LAST_NAME IN ('Doe')"));
});

it('get() eagerly pages through the FeatureServer into a Collection', function () {
    Http::fake([
        '*resultOffset=0*' => Http::response([
            'features' => [
                ['attributes' => ['LEG_DISTRICT' => '6', 'OBJECTID' => 1]],
            ],
            'exceededTransferLimit' => true,
        ]),
        '*resultOffset=1*' => Http::response([
            'features' => [
                ['attributes' => ['LEG_DISTRICT' => '7', 'OBJECTID' => 2]],
            ],
            'exceededTransferLimit' => false,
        ]),
    ]);

    $districts = ChesCoGis::congressionalDistricts()->get();

    expect($districts)->toBeInstanceOf(Collection::class)
        ->and($districts->map(fn ($district) => $district->districtNumber)->all())->toBe(['6', '7']);
});
