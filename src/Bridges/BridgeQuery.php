<?php

namespace WiserWebSolutions\ChesCoGis\Bridges;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use WiserWebSolutions\ChesCoGis\ArcGisClient;
use WiserWebSolutions\ChesCoGis\Support\BuildsWhereClauses;
use WiserWebSolutions\ChesCoGis\Support\FeatureLayer;

class BridgeQuery
{
    use BuildsWhereClauses;

    protected const OUT_FIELDS = 'BRIDGE_ID,PENNDOT_ID,BRIDGE_NAME,LOCATION,FEATURE_CARRIED,FEATURE_INTERSECTED,'
        .'OWNER_DESC,LENGTH,YEAR_BUILT,DECK_CONDITION,SUPERSTRUCTURE_CONDITION,SUBSTRUCTURE_CONDITION,'
        .'STRUCT_DEFICIENT,FUNC_OBSOLETE,SUFFICIENCY_RATING,OBJECTID';

    protected bool $withGeometry = true;

    public function __construct(
        protected ArcGisClient $client,
    ) {}

    /**
     * Restrict to one or more bridge identifiers.
     */
    public function whereBridgeId(string ...$bridgeIds): self
    {
        return $this->whereIn('BRIDGE_ID', $bridgeIds);
    }

    /**
     * Restrict to bridges flagged (or not flagged) as structurally deficient.
     */
    public function whereStructurallyDeficient(bool $deficient = true): self
    {
        return $this->where("STRUCT_DEFICIENT = '".($deficient ? 'Y' : 'N')."'");
    }

    /**
     * Restrict to bridges flagged (or not flagged) as functionally obsolete.
     */
    public function whereFunctionallyObsolete(bool $obsolete = true): self
    {
        return $this->where("FUNC_OBSOLETE = '".($obsolete ? 'Y' : 'N')."'");
    }

    /**
     * Restrict to bridges with a sufficiency rating at or below the given threshold.
     */
    public function whereSufficiencyRatingBelow(float $threshold): self
    {
        return $this->where("SUFFICIENCY_RATING <= {$threshold}");
    }

    /**
     * Restrict to bridges with a sufficiency rating between the given bounds (inclusive).
     */
    public function whereSufficiencyRatingBetween(int|float $min, int|float $max): self
    {
        return $this->whereBetween('SUFFICIENCY_RATING', $min, $max);
    }

    /**
     * Skip fetching point geometry, returning bridge attributes only.
     */
    public function withoutGeometry(): self
    {
        $this->withGeometry = false;

        return $this;
    }

    /**
     * Lazily stream every matching bridge, paging through the FeatureServer as it's consumed.
     *
     * @return LazyCollection<int, Bridge>
     */
    public function each(): LazyCollection
    {
        return LazyCollection::make(function () {
            foreach ($this->client->eachPage($this->layer(), $this->params()) as $features) {
                foreach ($features as $feature) {
                    yield Bridge::fromFeature($feature);
                }
            }
        });
    }

    /**
     * Eagerly fetch every matching bridge into a Collection, paging through the FeatureServer to completion.
     *
     * @return Collection<int, Bridge>
     */
    public function get(): Collection
    {
        return $this->each()->collect();
    }

    protected function layer(): FeatureLayer
    {
        return FeatureLayer::fromConfig('bridges');
    }

    /**
     * @return array<string, mixed>
     */
    protected function params(): array
    {
        return [
            'where' => $this->compileWhere(),
            'outFields' => self::OUT_FIELDS,
            'returnGeometry' => $this->withGeometry ? 'true' : 'false',
            'outSR' => '4326',
            'orderByFields' => 'OBJECTID',
        ];
    }
}
