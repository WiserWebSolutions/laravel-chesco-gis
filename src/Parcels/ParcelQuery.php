<?php

namespace WiserWebSolutions\ChesCoGis\Parcels;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use WiserWebSolutions\ChesCoGis\ArcGisClient;
use WiserWebSolutions\ChesCoGis\Support\BuildsWhereClauses;
use WiserWebSolutions\ChesCoGis\Support\FeatureLayer;

class ParcelQuery
{
    use BuildsWhereClauses;

    protected const OUT_FIELDS = 'UPI,LOC_ADDRESS,MUNI,SCHDIST,TOT_ASSESS,OWN1,LUC,CLASS,OBJECTID';

    public function __construct(
        protected ArcGisClient $client,
    ) {}

    /**
     * Restrict to one or more parcel identifiers.
     */
    public function whereUpi(string ...$upis): self
    {
        return $this->whereIn('UPI', $upis);
    }

    /**
     * Restrict to one or more municipalities.
     */
    public function whereMunicipality(MunicipalityEnum ...$municipalities): self
    {
        return $this->whereIn('MUNI', array_map(fn ($municipality) => $municipality->value, $municipalities), quote: false);
    }

    /**
     * Restrict to one or more school districts.
     */
    public function whereSchoolDistrict(SchoolDistrictEnum ...$schoolDistricts): self
    {
        return $this->whereIn('SCHDIST', array_map(fn ($schoolDistrict) => $schoolDistrict->value, $schoolDistricts));
    }

    /**
     * Restrict to one or more land use codes.
     */
    public function whereLandUse(LandUseEnum ...$landUses): self
    {
        return $this->whereIn('LUC', array_map(fn ($landUse) => $landUse->value, $landUses));
    }

    /**
     * Restrict to one or more property classes.
     */
    public function wherePropertyClass(PropertyClassEnum ...$propertyClasses): self
    {
        return $this->whereIn('CLASS', array_map(fn ($propertyClass) => $propertyClass->value, $propertyClasses));
    }

    /**
     * Restrict to parcels with a total assessed value between the given bounds (inclusive).
     */
    public function whereAssessedValueBetween(int|float $min, int|float $max): self
    {
        return $this->whereBetween('TOT_ASSESS', $min, $max);
    }

    /**
     * Lazily stream every matching parcel, paging through the FeatureServer as it's consumed.
     *
     * @return LazyCollection<int, Parcel>
     */
    public function each(): LazyCollection
    {
        return LazyCollection::make(function () {
            foreach ($this->client->eachPage($this->layer(), $this->params()) as $features) {
                foreach ($features as $feature) {
                    yield Parcel::fromFeature($feature);
                }
            }
        });
    }

    /**
     * Eagerly fetch every matching parcel into a Collection, paging through the FeatureServer to completion.
     *
     * @return Collection<int, Parcel>
     */
    public function get(): Collection
    {
        return $this->each()->collect();
    }

    protected function layer(): FeatureLayer
    {
        return FeatureLayer::fromConfig('parcels');
    }

    /**
     * @return array<string, mixed>
     */
    protected function params(): array
    {
        return [
            'where' => $this->compileWhere(),
            'outFields' => self::OUT_FIELDS,
            'returnGeometry' => 'false',
            'orderByFields' => 'OBJECTID',
        ];
    }
}
