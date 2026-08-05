<?php

use WiserWebSolutions\ChesCoGis\Districts\District;

it('maps ArcGIS attribute names to typed properties', function () {
    $feature = District::fromFeature([
        'attributes' => [
            'LEG_DISTRICT' => '6',
            'FIRST_NAME' => 'Jane',
            'LAST_NAME' => 'Doe',
            'OBJECTID' => 42,
        ],
        'geometry' => ['rings' => [[[0, 0], [0, 1], [1, 1], [0, 0]]]],
    ]);

    expect($feature->districtNumber)->toBe('6')
        ->and($feature->representativeFirstName)->toBe('Jane')
        ->and($feature->representativeLastName)->toBe('Doe')
        ->and($feature->sourceObjectId)->toBe(42)
        ->and($feature->geometry)->toBe(['rings' => [[[0, 0], [0, 1], [1, 1], [0, 0]]]]);
});

it('allows a null geometry when the query excludes it', function () {
    $feature = District::fromFeature([
        'attributes' => ['LEG_DISTRICT' => '6'],
    ]);

    expect($feature->geometry)->toBeNull();
});

it('throws when the feature has no LEG_DISTRICT', function () {
    District::fromFeature(['attributes' => ['FIRST_NAME' => 'Jane']]);
})->throws(InvalidArgumentException::class);
