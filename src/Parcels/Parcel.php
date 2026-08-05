<?php

namespace WiserWebSolutions\ChesCoGis\Parcels;

readonly class Parcel
{
    public function __construct(
        public string $upi,
        public ?string $locAddress,
        public ?StreetDirectionEnum $streetDirection,
        public ?MunicipalityEnum $municipality,
        public ?SchoolDistrictEnum $schoolDistrict,
        public ?float $totalAssessment,
        public ?string $ownerName,
        public ?LandUseEnum $landUse,
        public ?PropertyClassEnum $propertyClass,
        public ?int $sourceObjectId,
        /** @var array<string, mixed> raw ArcGIS `attributes` payload, for anything not mapped above */
        public array $raw,
    ) {}

    /**
     * @param  array<string, mixed>  $feature  one entry from a FeatureServer query response's `features` array
     */
    public static function fromFeature(array $feature): self
    {
        $attributes = $feature['attributes'] ?? [];
        $upi = $attributes['UPI'] ?? null;

        if (! $upi) {
            throw new \InvalidArgumentException('Parcel feature missing UPI.');
        }

        return new self(
            upi: $upi,
            locAddress: $attributes['LOC_ADDRESS'] ?? null,
            streetDirection: isset($attributes['DIR']) ? StreetDirectionEnum::tryFrom((string) $attributes['DIR']) : null,
            municipality: isset($attributes['MUNI']) ? MunicipalityEnum::tryFrom((string) $attributes['MUNI']) : null,
            schoolDistrict: isset($attributes['SCHDIST']) ? SchoolDistrictEnum::tryFrom((string) $attributes['SCHDIST']) : null,
            totalAssessment: isset($attributes['TOT_ASSESS']) ? (float) $attributes['TOT_ASSESS'] : null,
            ownerName: $attributes['OWN1'] ?? null,
            landUse: isset($attributes['LUC']) ? LandUseEnum::tryFrom((string) $attributes['LUC']) : null,
            propertyClass: isset($attributes['CLASS']) ? PropertyClassEnum::tryFrom((string) $attributes['CLASS']) : null,
            sourceObjectId: isset($attributes['OBJECTID']) ? (int) $attributes['OBJECTID'] : null,
            raw: $attributes,
        );
    }

    /**
     * Stable hash of the raw attributes, useful for skipping unchanged rows on re-sync.
     */
    public function hash(): string
    {
        return hash('sha256', json_encode($this->raw));
    }
}
