<?php

namespace WiserWebSolutions\ChesCoGis\Support;

readonly class FeatureLayer
{
    public function __construct(
        public string $baseUrl,
        public int $layerId = 0,
    ) {}

    public static function fromConfig(string $key): self
    {
        $layer = config("chesco-gis.layers.{$key}");

        if ($layer === null) {
            throw new \InvalidArgumentException("No ChesCoGis layer configured for [{$key}]. Add it to config/chesco-gis.php.");
        }

        return new self($layer['url'], (int) ($layer['layer_id'] ?? 0));
    }

    public function url(): string
    {
        return rtrim($this->baseUrl, '/').'/'.$this->layerId;
    }

    public function queryUrl(): string
    {
        return $this->url().'/query';
    }
}
