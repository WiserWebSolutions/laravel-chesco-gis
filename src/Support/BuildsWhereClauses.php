<?php

namespace WiserWebSolutions\ChesCoGis\Support;

/**
 * Shared ArcGIS `where` clause accumulation for query builders — each call adds
 * an AND-combined condition, similar to Laravel's own query builder.
 */
trait BuildsWhereClauses
{
    /** @var array<int, string> */
    protected array $wheres = [];

    /**
     * Add a raw ArcGIS `where` condition (e.g. "TOT_ASSESS > 100000"), AND-combined with any others.
     */
    public function where(string $clause): static
    {
        $this->wheres[] = $clause;

        return $this;
    }

    /**
     * Add a "field IN (...)" condition for one or more values.
     *
     * @param  array<int, string|int|float>  $values
     */
    protected function whereIn(string $field, array $values, bool $quote = true): static
    {
        if ($values === []) {
            throw new \InvalidArgumentException("whereIn() for [{$field}] requires at least one value.");
        }

        $values = array_map(
            fn ($value) => $quote ? "'".str_replace("'", "''", (string) $value)."'" : (string) $value,
            $values,
        );

        return $this->where("{$field} IN (".implode(', ', $values).')');
    }

    /**
     * Add a "field BETWEEN min AND max" condition (inclusive of both bounds).
     */
    protected function whereBetween(string $field, int|float $min, int|float $max): static
    {
        return $this->where("{$field} BETWEEN {$min} AND {$max}");
    }

    protected function compileWhere(): string
    {
        return $this->wheres === [] ? '1=1' : implode(' AND ', $this->wheres);
    }
}
