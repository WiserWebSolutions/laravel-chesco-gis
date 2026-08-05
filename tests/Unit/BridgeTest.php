<?php

use WiserWebSolutions\ChesCoGis\Bridges\Bridge;

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
            'LENGTH' => '120.5',
            'YEAR_BUILT' => '1955',
            'DECK_CONDITION' => 'G',
            'SUPERSTRUCTURE_CONDITION' => 'G',
            'SUBSTRUCTURE_CONDITION' => 'F',
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
        ->and($feature->lengthFeet)->toBe(120.5)
        ->and($feature->yearBuilt)->toBe('1955')
        ->and($feature->deckCondition)->toBe('G')
        ->and($feature->superstructureCondition)->toBe('G')
        ->and($feature->substructureCondition)->toBe('F')
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
        ->and($feature->structurallyDeficient)->toBeNull();
});

it('throws when the feature has no BRIDGE_ID', function () {
    Bridge::fromFeature(['attributes' => ['BRIDGE_NAME' => 'Main St Bridge']]);
})->throws(InvalidArgumentException::class);
