<?php

namespace WiserWebSolutions\ChesCoGis\Parcels;

/**
 * Standard street pre-directional abbreviation, the parcel `DIR` field
 * (e.g. the "N" in "123 N Main St").
 */
enum StreetDirectionEnum: string
{
    case North = 'N';
    case South = 'S';
    case East = 'E';
    case West = 'W';
    case Northeast = 'NE';
    case Northwest = 'NW';
    case Southeast = 'SE';
    case Southwest = 'SW';

    public function description(): string
    {
        return match ($this) {
            self::North => 'North',
            self::South => 'South',
            self::East => 'East',
            self::West => 'West',
            self::Northeast => 'Northeast',
            self::Northwest => 'Northwest',
            self::Southeast => 'Southeast',
            self::Southwest => 'Southwest',
        };
    }
}
