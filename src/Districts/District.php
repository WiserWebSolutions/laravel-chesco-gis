<?php

namespace WiserWebSolutions\ChesCoGis\Districts;

readonly class District
{
    public function __construct(
        public string $districtNumber,
        public ?string $representativeFirstName,
        public ?string $representativeLastName,
        public ?int $sourceObjectId,
        /** @var array<string, mixed>|null esri geometry (e.g. `rings`) for this district's boundary, present when the query includes geometry */
        public ?array $geometry,
        /** @var array<string, mixed> raw ArcGIS `attributes` payload, for anything not mapped above */
        public array $raw,
    ) {}

    /**
     * @param  array<string, mixed>  $feature  one entry from a FeatureServer query response's `features` array
     */
    public static function fromFeature(array $feature): self
    {
        $attributes = $feature['attributes'] ?? [];
        $districtNumber = $attributes['LEG_DISTRICT'] ?? null;

        if (! $districtNumber) {
            throw new \InvalidArgumentException('District feature missing LEG_DISTRICT.');
        }

        return new self(
            districtNumber: (string) $districtNumber,
            representativeFirstName: $attributes['FIRST_NAME'] ?? null,
            representativeLastName: $attributes['LAST_NAME'] ?? null,
            sourceObjectId: isset($attributes['OBJECTID']) ? (int) $attributes['OBJECTID'] : null,
            geometry: $feature['geometry'] ?? null,
            raw: $attributes,
        );
    }
}
