<?php

use WiserWebSolutions\ChesCoGis\Bridges\Bridge;
use WiserWebSolutions\ChesCoGis\Bridges\BridgeConditionEnum;
use WiserWebSolutions\ChesCoGis\Bridges\BridgeOwnerEnum;
use WiserWebSolutions\ChesCoGis\Bridges\BridgePostStatusEnum;

it('maps ArcGIS attribute names to typed properties', function () {
    $feature = Bridge::fromFeature([
        'attributes' => [
            'BRIDGE_ID' => '15-1234',
            'PENNDOT_ID' => 12345,
            'BRIDGE_NAME' => 'Main St Bridge',
            'LOCATION' => 'Main St over Brandywine Creek',
            'FEATURE_CARRIED' => 'Main St',
            'FEATURE_INTERSECTED' => 'Brandywine Creek',
            'OWNER_DESC' => 'County',
            'OWNER_CODE' => '02',
            'LENGTH' => '120.5',
            'YEAR_BUILT' => '1955',
            'DECK_CONDITION' => '7',
            'SUPERSTRUCTURE_CONDITION' => '7',
            'SUBSTRUCTURE_CONDITION' => '5',
            'CULVERT_CONDITION' => 'N',
            'POST_STATUS' => 'open',
            'STRUCT_DEFICIENT' => 'Y',
            'FUNC_OBSOLETE' => 'N',
            'SUFFICIENCY_RATING' => '62.3',
            'OBJECTID' => 42,
        ],
        'geometry' => ['x' => -75.6, 'y' => 39.9],
    ]);

    expect($feature->bridgeId)->toBe('15-1234')
        ->and($feature->pennDotId)->toBe(12345)
        ->and($feature->name)->toBe('Main St Bridge')
        ->and($feature->location)->toBe('Main St over Brandywine Creek')
        ->and($feature->featureCarried)->toBe('Main St')
        ->and($feature->featureIntersected)->toBe('Brandywine Creek')
        ->and($feature->ownerDescription)->toBe('County')
        ->and($feature->ownerCode)->toBe(BridgeOwnerEnum::CountyHighwayAgency)
        ->and($feature->lengthFeet)->toBe(120.5)
        ->and($feature->yearBuilt)->toBe('1955')
        ->and($feature->deckCondition)->toBe(BridgeConditionEnum::Good)
        ->and($feature->superstructureCondition)->toBe(BridgeConditionEnum::Good)
        ->and($feature->substructureCondition)->toBe(BridgeConditionEnum::Fair)
        ->and($feature->substructureCondition->rating())->toBe(5)
        ->and($feature->culvertCondition)->toBe(BridgeConditionEnum::NotApplicable)
        ->and($feature->postStatus)->toBe(BridgePostStatusEnum::Open)
        ->and($feature->structurallyDeficient)->toBeTrue()
        ->and($feature->functionallyObsolete)->toBeFalse()
        ->and($feature->sufficiencyRating)->toBe(62.3)
        ->and($feature->sourceObjectId)->toBe(42)
        ->and($feature->geometry)->toBe(['x' => -75.6, 'y' => 39.9]);
});

it('allows a null geometry when the query excludes it', function () {
    $feature = Bridge::fromFeature([
        'attributes' => ['BRIDGE_ID' => '15-1234'],
    ]);

    expect($feature->geometry)->toBeNull()
        ->and($feature->structurallyDeficient)->toBeNull()
        ->and($feature->deckCondition)->toBeNull();
});

it('maps the "N" condition code to BridgeConditionEnum::NotApplicable', function () {
    $feature = Bridge::fromFeature([
        'attributes' => ['BRIDGE_ID' => '15-1234', 'DECK_CONDITION' => 'N'],
    ]);

    expect($feature->deckCondition)->toBe(BridgeConditionEnum::NotApplicable)
        ->and($feature->deckCondition->rating())->toBeNull();
});

it('falls back to null for an unrecognized condition code', function () {
    $feature = Bridge::fromFeature([
        'attributes' => ['BRIDGE_ID' => '15-1234', 'DECK_CONDITION' => 'Z'],
    ]);

    expect($feature->deckCondition)->toBeNull();
});

it('throws when the feature has no BRIDGE_ID', function () {
    Bridge::fromFeature(['attributes' => ['BRIDGE_NAME' => 'Main St Bridge']]);
})->throws(InvalidArgumentException::class);

it('resolves BridgeOwnerEnum from unpadded codes, padded codes, and spelled-out descriptions', function () {
    expect(BridgeOwnerEnum::fromRaw('1'))->toBe(BridgeOwnerEnum::StateHighwayAgency)
        ->and(BridgeOwnerEnum::fromRaw('01'))->toBe(BridgeOwnerEnum::StateHighwayAgency)
        ->and(BridgeOwnerEnum::fromRaw('STATE HIGHWAY AGENCY'))->toBe(BridgeOwnerEnum::StateHighwayAgency)
        ->and(BridgeOwnerEnum::fromRaw('TOWN OR TOWNSHIP AGENCY'))->toBe(BridgeOwnerEnum::TownOrTownshipHighwayAgency)
        ->and(BridgeOwnerEnum::fromRaw('PRIVATE (OTHER THAN RAILROAD)'))->toBe(BridgeOwnerEnum::Private)
        ->and(BridgeOwnerEnum::fromRaw('Rick'))->toBeNull()
        ->and(BridgeOwnerEnum::fromRaw(' '))->toBeNull()
        ->and(BridgeOwnerEnum::fromRaw(''))->toBeNull();
});

it('normalizes POST_STATUS casing and whitespace before mapping to BridgePostStatusEnum', function () {
    $feature = Bridge::fromFeature([
        'attributes' => ['BRIDGE_ID' => '15-1234', 'POST_STATUS' => ' Closed '],
    ]);

    expect($feature->postStatus)->toBe(BridgePostStatusEnum::Closed);
});
