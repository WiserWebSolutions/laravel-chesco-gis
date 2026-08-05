<?php

namespace WiserWebSolutions\ChesCoGis;

use Generator;
use Illuminate\Http\Client\Factory as HttpFactory;
use WiserWebSolutions\ChesCoGis\Exceptions\ArcGisQueryException;
use WiserWebSolutions\ChesCoGis\Support\FeatureLayer;

class ArcGisClient
{
    public function __construct(
        protected HttpFactory $http,
    ) {}

    /**
     * @param  array<string, mixed>  $params  where/outFields/orderByFields/etc — resultOffset, resultRecordCount, and f are set for you.
     * @return array<string, mixed> decoded FeatureServer query response
     */
    public function queryPage(FeatureLayer $layer, array $params, int $offset = 0, ?int $pageSize = null): array
    {
        $response = $this->http
            ->timeout(config('chesco-gis.timeout', 120))
            ->retry(config('chesco-gis.retry_times', 3), config('chesco-gis.retry_sleep_ms', 1000))
            ->get($layer->queryUrl(), array_merge($params, [
                'resultOffset' => $offset,
                'resultRecordCount' => $pageSize ?? config('chesco-gis.page_size', 2000),
                'f' => 'json',
            ]));

        $response->throw();

        $payload = $response->json();

        if (isset($payload['error'])) {
            throw new ArcGisQueryException($payload['error']['message'] ?? 'ArcGIS query failed.');
        }

        return $payload;
    }

    /**
     * Pages through every feature for a query, yielding one page's `features` array at a time.
     *
     * @param  array<string, mixed>  $params
     * @return Generator<int, array<int, array<string, mixed>>>
     */
    public function eachPage(FeatureLayer $layer, array $params, ?int $pageSize = null): Generator
    {
        $offset = 0;

        do {
            $payload = $this->queryPage($layer, $params, $offset, $pageSize);
            $features = $payload['features'] ?? [];

            yield $features;

            $offset += count($features);
            $hasMore = ($payload['exceededTransferLimit'] ?? false) && count($features) > 0;
        } while ($hasMore);
    }
}
