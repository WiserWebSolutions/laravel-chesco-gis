<?php

namespace WiserWebSolutions\ChesCoGis\Bridges;

readonly class Bridge
{
    public function __construct(
        public string $bridgeId,
        public ?int $pennDotId,
        public ?string $name,
        public ?string $location,
        public ?string $featureCarried,
        public ?string $featureIntersected,
        public ?string $ownerDescription,
        public ?float $lengthFeet,
        public ?string $yearBuilt,
        public ?BridgeConditionEnum $deckCondition,
        public ?BridgeConditionEnum $superstructureCondition,
        public ?BridgeConditionEnum $substructureCondition,
        public ?BridgeConditionEnum $culvertCondition,
        public ?BridgeOwnerEnum $ownerCode,
        public ?BridgePostStatusEnum $postStatus,
        public ?bool $structurallyDeficient,
        public ?bool $functionallyObsolete,
        public ?float $sufficiencyRating,
        public ?int $sourceObjectId,
        /** @var array<string, mixed>|null esri point geometry (`x`/`y`), present when the query includes geometry */
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
        $bridgeId = $attributes['BRIDGE_ID'] ?? null;

        if (! $bridgeId) {
            throw new \InvalidArgumentException('Bridge feature missing BRIDGE_ID.');
        }

        return new self(
            bridgeId: (string) $bridgeId,
            pennDotId: isset($attributes['PENNDOT_ID']) ? (int) $attributes['PENNDOT_ID'] : null,
            name: $attributes['BRIDGE_NAME'] ?? null,
            location: $attributes['LOCATION'] ?? null,
            featureCarried: $attributes['FEATURE_CARRIED'] ?? null,
            featureIntersected: $attributes['FEATURE_INTERSECTED'] ?? null,
            ownerDescription: $attributes['OWNER_DESC'] ?? null,
            lengthFeet: isset($attributes['LENGTH']) ? (float) $attributes['LENGTH'] : null,
            yearBuilt: isset($attributes['YEAR_BUILT']) ? (string) $attributes['YEAR_BUILT'] : null,
            deckCondition: isset($attributes['DECK_CONDITION']) ? BridgeConditionEnum::tryFrom((string) $attributes['DECK_CONDITION']) : null,
            superstructureCondition: isset($attributes['SUPERSTRUCTURE_CONDITION']) ? BridgeConditionEnum::tryFrom((string) $attributes['SUPERSTRUCTURE_CONDITION']) : null,
            substructureCondition: isset($attributes['SUBSTRUCTURE_CONDITION']) ? BridgeConditionEnum::tryFrom((string) $attributes['SUBSTRUCTURE_CONDITION']) : null,
            culvertCondition: isset($attributes['CULVERT_CONDITION']) ? BridgeConditionEnum::tryFrom((string) $attributes['CULVERT_CONDITION']) : null,
            ownerCode: isset($attributes['OWNER_CODE']) ? BridgeOwnerEnum::fromRaw((string) $attributes['OWNER_CODE']) : null,
            postStatus: isset($attributes['POST_STATUS']) ? BridgePostStatusEnum::tryFrom(strtoupper(trim((string) $attributes['POST_STATUS']))) : null,
            structurallyDeficient: isset($attributes['STRUCT_DEFICIENT']) ? strtoupper((string) $attributes['STRUCT_DEFICIENT']) === 'Y' : null,
            functionallyObsolete: isset($attributes['FUNC_OBSOLETE']) ? strtoupper((string) $attributes['FUNC_OBSOLETE']) === 'Y' : null,
            sufficiencyRating: isset($attributes['SUFFICIENCY_RATING']) ? (float) $attributes['SUFFICIENCY_RATING'] : null,
            sourceObjectId: isset($attributes['OBJECTID']) ? (int) $attributes['OBJECTID'] : null,
            geometry: $feature['geometry'] ?? null,
            raw: $attributes,
        );
    }
}
