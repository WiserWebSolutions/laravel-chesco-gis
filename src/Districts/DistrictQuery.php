<?php

namespace WiserWebSolutions\ChesCoGis\Districts;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use WiserWebSolutions\ChesCoGis\ArcGisClient;
use WiserWebSolutions\ChesCoGis\Support\BuildsWhereClauses;
use WiserWebSolutions\ChesCoGis\Support\FeatureLayer;

/**
 * Shared query builder for Chester County's legislative district boundary layers
 * (US Congressional, State Senate, State House) — they all share the same schema.
 */
class DistrictQuery
{
    use BuildsWhereClauses;

    protected const OUT_FIELDS = 'LEG_DISTRICT,LAST_NAME,FIRST_NAME,OBJECTID';

    protected bool $withGeometry = true;

    public function __construct(
        protected ArcGisClient $client,
        protected string $layerKey,
    ) {}

    /**
     * Restrict to one or more district numbers.
     */
    public function whereDistrictNumber(int ...$districtNumbers): self
    {
        return $this->whereIn('LEG_DISTRICT', $districtNumbers);
    }

    /**
     * Restrict to one or more representatives' last names.
     */
    public function whereLastName(string ...$lastNames): self
    {
        return $this->whereIn('LAST_NAME', $lastNames);
    }

    /**
     * Skip fetching boundary geometry, returning district/legislator attributes only.
     */
    public function withoutGeometry(): self
    {
        $this->withGeometry = false;

        return $this;
    }

    /**
     * Lazily stream every matching district, paging through the FeatureServer as it's consumed.
     *
     * @return LazyCollection<int, District>
     */
    public function each(): LazyCollection
    {
        return LazyCollection::make(function () {
            foreach ($this->client->eachPage($this->layer(), $this->params()) as $features) {
                foreach ($features as $feature) {
                    yield District::fromFeature($feature);
                }
            }
        });
    }

    /**
     * Eagerly fetch every matching district into a Collection, paging through the FeatureServer to completion.
     *
     * @return Collection<int, District>
     */
    public function get(): Collection
    {
        return $this->each()->collect();
    }

    protected function layer(): FeatureLayer
    {
        return FeatureLayer::fromConfig($this->layerKey);
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
