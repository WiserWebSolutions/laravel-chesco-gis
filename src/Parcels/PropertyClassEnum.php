<?php

namespace WiserWebSolutions\ChesCoGis\Parcels;

/**
 * Chester County assessment office property class codes (the `CLASS` parcel field) —
 * the general land use category each {@see LandUseEnum} rolls up to.
 *
 * @see https://www.chesco.org/DocumentCenter/View/8673/Land_Use_Codes
 */
enum PropertyClassEnum: string
{
    case Apartment = 'A';
    case Commercial = 'C';
    case Exempt = 'E';
    case Farm = 'F';
    case Industrial = 'I';
    case NotAssessed = 'N';
    case Residential = 'R';
    case Utility = 'U';

    public function description(): string
    {
        return match ($this) {
            self::Apartment => 'Apartment',
            self::Commercial => 'Commercial',
            self::Exempt => 'Exempt',
            self::Farm => 'Farm',
            self::Industrial => 'Industrial',
            self::NotAssessed => 'Not Assessed',
            self::Residential => 'Residential',
            self::Utility => 'Utility',
        };
    }

    /**
     * Every {@see LandUseEnum} that rolls up to this class — the inverse of {@see LandUseEnum::propertyClass()}.
     *
     * @return array<int, LandUseEnum>
     */
    public function landUses(): array
    {
        return array_values(array_filter(
            LandUseEnum::cases(),
            fn (LandUseEnum $landUse) => $landUse->propertyClass() === $this,
        ));
    }
}
