<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use WiserWebSolutions\ChesCoGis\Exceptions\ArcGisQueryException;
use WiserWebSolutions\ChesCoGis\Facades\ChesCoGis;

it('pages through the FeatureServer and requests geometry by default', function () {
    Http::fake([
        '*resultOffset=0*' => Http::response([
            'features' => [
                ['attributes' => ['BRIDGE_ID' => '15-1', 'OBJECTID' => 1]],
            ],
            'exceededTransferLimit' => true,
        ]),
        '*resultOffset=1*' => Http::response([
            'features' => [
                ['attributes' => ['BRIDGE_ID' => '15-2', 'OBJECTID' => 2]],
            ],
            'exceededTransferLimit' => false,
        ]),
    ]);

    $bridgeIds = ChesCoGis::bridges()
        ->each()
        ->map(fn ($bridge) => $bridge->bridgeId)
        ->all();

    expect($bridgeIds)->toBe(['15-1', '15-2']);

    Http::assertSent(fn ($request) => str_contains((string) $request->url(), 'returnGeometry=true'));
});

it('skips geometry when withoutGeometry is called', function () {
    Http::fake([
        '*' => Http::response([
            'features' => [
                ['attributes' => ['BRIDGE_ID' => '15-1', 'OBJECTID' => 1]],
            ],
            'exceededTransferLimit' => false,
        ]),
    ]);

    ChesCoGis::bridges()->withoutGeometry()->each()->all();

    Http::assertSent(fn ($request) => str_contains((string) $request->url(), 'returnGeometry=false'));
});

it('applies a custom where clause', function () {
    Http::fake([
        '*' => Http::response([
            'features' => [
                ['attributes' => ['BRIDGE_ID' => '15-1', 'OBJECTID' => 1]],
            ],
            'exceededTransferLimit' => false,
        ]),
    ]);

    ChesCoGis::bridges()->where("STRUCT_DEFICIENT = 'Y'")->each()->all();

    Http::assertSent(fn ($request) => str_contains(urldecode((string) $request->url()), "where=STRUCT_DEFICIENT = 'Y'"));
});

it('throws when the FeatureServer returns an ArcGIS error payload', function () {
    Http::fake([
        '*' => Http::response(['error' => ['message' => 'Invalid where clause']]),
    ]);

    ChesCoGis::bridges()->each()->all();
})->throws(ArcGisQueryException::class, 'Invalid where clause');

it('filters by one or more bridge ids', function () {
    Http::fake(['*' => Http::response(['features' => [], 'exceededTransferLimit' => false])]);

    ChesCoGis::bridges()->whereBridgeId('15-1', '15-2')->each()->all();

    Http::assertSent(fn ($request) => str_contains(urldecode((string) $request->url()), "where=BRIDGE_ID IN ('15-1', '15-2')"));
});

it('filters by structurally deficient status', function () {
    Http::fake(['*' => Http::response(['features' => [], 'exceededTransferLimit' => false])]);

    ChesCoGis::bridges()->whereStructurallyDeficient()->each()->all();

    Http::assertSent(fn ($request) => str_contains(urldecode((string) $request->url()), "where=STRUCT_DEFICIENT = 'Y'"));

    ChesCoGis::bridges()->whereStructurallyDeficient(false)->each()->all();

    Http::assertSent(fn ($request) => str_contains(urldecode((string) $request->url()), "where=STRUCT_DEFICIENT = 'N'"));
});

it('filters by functionally obsolete status', function () {
    Http::fake(['*' => Http::response(['features' => [], 'exceededTransferLimit' => false])]);

    ChesCoGis::bridges()->whereFunctionallyObsolete()->each()->all();

    Http::assertSent(fn ($request) => str_contains(urldecode((string) $request->url()), "where=FUNC_OBSOLETE = 'Y'"));
});

it('filters by a sufficiency rating threshold', function () {
    Http::fake(['*' => Http::response(['features' => [], 'exceededTransferLimit' => false])]);

    ChesCoGis::bridges()->whereSufficiencyRatingBelow(50)->each()->all();

    Http::assertSent(fn ($request) => str_contains(urldecode((string) $request->url()), 'where=SUFFICIENCY_RATING <= 50'));
});

it('filters by a sufficiency rating range', function () {
    Http::fake(['*' => Http::response(['features' => [], 'exceededTransferLimit' => false])]);

    ChesCoGis::bridges()->whereSufficiencyRatingBetween(40, 60)->each()->all();

    Http::assertSent(fn ($request) => str_contains(urldecode((string) $request->url()), 'where=SUFFICIENCY_RATING BETWEEN 40 AND 60'));
});

it('get() eagerly pages through the FeatureServer into a Collection', function () {
    Http::fake([
        '*resultOffset=0*' => Http::response([
            'features' => [
                ['attributes' => ['BRIDGE_ID' => '15-1', 'OBJECTID' => 1]],
            ],
            'exceededTransferLimit' => true,
        ]),
        '*resultOffset=1*' => Http::response([
            'features' => [
                ['attributes' => ['BRIDGE_ID' => '15-2', 'OBJECTID' => 2]],
            ],
            'exceededTransferLimit' => false,
        ]),
    ]);

    $bridges = ChesCoGis::bridges()->get();

    expect($bridges)->toBeInstanceOf(Collection::class)
        ->and($bridges->map(fn ($bridge) => $bridge->bridgeId)->all())->toBe(['15-1', '15-2']);
});
