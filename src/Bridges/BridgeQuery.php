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
        .'OWNER_CODE,OWNER_DESC,LENGTH,YEAR_BUILT,DECK_CONDITION,SUPERSTRUCTURE_CONDITION,SUBSTRUCTURE_CONDITION,'
        .'CULVERT_CONDITION,POST_STATUS,STRUCT_DEFICIENT,FUNC_OBSOLETE,SUFFICIENCY_RATING,OBJECTID';

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
     * Restrict to one or more deck condition ratings.
     */
    public function whereDeckCondition(BridgeConditionEnum ...$conditions): self
    {
        return $this->whereIn('DECK_CONDITION', array_map(fn ($condition) => $condition->value, $conditions));
    }

    /**
     * Restrict to one or more superstructure condition ratings.
     */
    public function whereSuperstructureCondition(BridgeConditionEnum ...$conditions): self
    {
        return $this->whereIn('SUPERSTRUCTURE_CONDITION', array_map(fn ($condition) => $condition->value, $conditions));
    }

    /**
     * Restrict to one or more substructure condition ratings.
     */
    public function whereSubstructureCondition(BridgeConditionEnum ...$conditions): self
    {
        return $this->whereIn('SUBSTRUCTURE_CONDITION', array_map(fn ($condition) => $condition->value, $conditions));
    }

    /**
     * Restrict to one or more culvert condition ratings.
     */
    public function whereCulvertCondition(BridgeConditionEnum ...$conditions): self
    {
        return $this->whereIn('CULVERT_CONDITION', array_map(fn ($condition) => $condition->value, $conditions));
    }

    /**
     * Restrict to one or more owning agencies. Note: `OWNER_CODE` is inconsistently
     * entered upstream (some rows store the raw code, others the spelled-out
     * description) — this filters server-side on the code form only, so it may
     * miss rows recorded as text. {@see Bridge::$ownerCode} normalizes both forms
     * after fetching, so filtering client-side is more reliable if this matters.
     */
    public function whereOwnerCode(BridgeOwnerEnum ...$owners): self
    {
        return $this->whereIn('OWNER_CODE', array_map(fn ($owner) => $owner->value, $owners));
    }

    /**
     * Restrict to one or more posting statuses.
     */
    public function wherePostStatus(BridgePostStatusEnum ...$statuses): self
    {
        return $this->whereIn('POST_STATUS', array_map(fn ($status) => $status->value, $statuses));
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
