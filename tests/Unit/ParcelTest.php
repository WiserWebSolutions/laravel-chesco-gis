<?php

use WiserWebSolutions\ChesCoGis\Parcels\LandUseEnum;
use WiserWebSolutions\ChesCoGis\Parcels\MunicipalityEnum;
use WiserWebSolutions\ChesCoGis\Parcels\Parcel;
use WiserWebSolutions\ChesCoGis\Parcels\PropertyClassEnum;
use WiserWebSolutions\ChesCoGis\Parcels\SchoolDistrictEnum;

it('maps ArcGIS attribute names to typed properties', function () {
    $feature = Parcel::fromFeature([
        'attributes' => [
            'UPI' => '27-5-123',
            'LOC_ADDRESS' => '123 Main St',
            'MUNI' => '15',
            'SCHDIST' => '08',
            'TOT_ASSESS' => '150000',
            'OWN1' => 'Doe, John',
            'LUC' => 'R-10',
            'CLASS' => 'R',
            'OBJECTID' => 42,
        ],
    ]);

    expect($feature->upi)->toBe('27-5-123')
        ->and($feature->locAddress)->toBe('123 Main St')
        ->and($feature->municipality)->toBe(MunicipalityEnum::Phoenixville)
        ->and($feature->schoolDistrict)->toBe(SchoolDistrictEnum::Phoenixville)
        ->and($feature->totalAssessment)->toBe(150000.0)
        ->and($feature->ownerName)->toBe('Doe, John')
        ->and($feature->landUse)->toBe(LandUseEnum::R_10)
        ->and($feature->landUse->description())->toBe('Single Family/Cabin')
        ->and($feature->propertyClass)->toBe(PropertyClassEnum::Residential)
        ->and($feature->sourceObjectId)->toBe(42);
});

it('falls back to null for an unrecognized code', function () {
    $feature = Parcel::fromFeature([
        'attributes' => [
            'UPI' => '27-5-123',
            'LUC' => 'Z-99',
            'CLASS' => 'Z',
            'MUNI' => '999',
            'SCHDIST' => '99',
        ],
    ]);

    expect($feature->landUse)->toBeNull()
        ->and($feature->propertyClass)->toBeNull()
        ->and($feature->municipality)->toBeNull()
        ->and($feature->schoolDistrict)->toBeNull();
});

it('throws when the feature has no UPI', function () {
    Parcel::fromFeature(['attributes' => ['LOC_ADDRESS' => '123 Main St']]);
})->throws(InvalidArgumentException::class);

it('produces a stable hash for identical raw attributes', function () {
    $attributes = ['UPI' => '27-5-123', 'TOT_ASSESS' => '150000'];

    $a = Parcel::fromFeature(['attributes' => $attributes]);
    $b = Parcel::fromFeature(['attributes' => $attributes]);

    expect($a->hash())->toBe($b->hash());
});

it('resolves the land uses that roll up to a property class, the inverse of LandUseEnum::propertyClass()', function () {
    expect(PropertyClassEnum::Residential->landUses())->toContain(LandUseEnum::R_10, LandUseEnum::V_10)
        ->and(PropertyClassEnum::Apartment->landUses())->toBe([LandUseEnum::R_40, LandUseEnum::R_90]);

    foreach (PropertyClassEnum::cases() as $propertyClass) {
        foreach ($propertyClass->landUses() as $landUse) {
            expect($landUse->propertyClass())->toBe($propertyClass);
        }
    }
});

it('resolves the municipalities within a school district, the inverse of MunicipalityEnum::schoolDistricts()', function () {
    expect(SchoolDistrictEnum::SpringFord->municipalities())->toBe([MunicipalityEnum::SpringCity])
        ->and(MunicipalityEnum::SpringCity->schoolDistricts())->toBe([SchoolDistrictEnum::SpringFord]);

    foreach (SchoolDistrictEnum::cases() as $schoolDistrict) {
        foreach ($schoolDistrict->municipalities() as $municipality) {
            expect($municipality->schoolDistricts())->toContain($schoolDistrict);
        }
    }
});

it('covers every municipality code exactly once, except the split East Marlborough township', function () {
    $counts = [];

    foreach (SchoolDistrictEnum::cases() as $schoolDistrict) {
        foreach ($schoolDistrict->municipalities() as $municipality) {
            $counts[$municipality->value] = ($counts[$municipality->value] ?? 0) + 1;
        }
    }

    foreach (MunicipalityEnum::cases() as $municipality) {
        $expected = $municipality === MunicipalityEnum::EastMarlborough ? 2 : 1;
        expect($counts[$municipality->value] ?? 0)->toBe($expected);
    }
});
