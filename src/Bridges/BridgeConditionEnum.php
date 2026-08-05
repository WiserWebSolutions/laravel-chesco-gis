<?php

namespace WiserWebSolutions\ChesCoGis\Bridges;

/**
 * FHWA National Bridge Inventory (NBI) condition rating scale, used for the
 * `DECK_CONDITION`, `SUPERSTRUCTURE_CONDITION`, and `SUBSTRUCTURE_CONDITION` fields.
 *
 * @see https://www.fhwa.dot.gov/bridge/mtguide.pdf
 */
enum BridgeConditionEnum: string
{
    case Excellent = '9';
    case VeryGood = '8';
    case Good = '7';
    case Satisfactory = '6';
    case Fair = '5';
    case Poor = '4';
    case Serious = '3';
    case Critical = '2';
    case ImminentFailure = '1';
    case Failed = '0';
    case NotApplicable = 'N';

    public function description(): string
    {
        return match ($this) {
            self::Excellent => 'Excellent Condition',
            self::VeryGood => 'Very Good Condition - no problems noted',
            self::Good => 'Good Condition - some minor problems',
            self::Satisfactory => 'Satisfactory Condition - structural elements show some minor deterioration',
            self::Fair => 'Fair Condition - all primary structural elements are sound but may have minor section loss, cracking, spalling or scour',
            self::Poor => 'Poor Condition - advanced section loss, deterioration, spalling or scour',
            self::Serious => 'Serious Condition - loss of section, deterioration of primary structural elements',
            self::Critical => 'Critical Condition - advanced deterioration of primary structural elements',
            self::ImminentFailure => 'Imminent Failure Condition - major deterioration or section loss present in critical structural components',
            self::Failed => 'Failed Condition - out of service, beyond corrective action',
            self::NotApplicable => 'Not Applicable',
        };
    }

    /**
     * The numeric NBI rating (0-9), or null for {@see self::NotApplicable}.
     */
    public function rating(): ?int
    {
        return $this === self::NotApplicable ? null : (int) $this->value;
    }
}
