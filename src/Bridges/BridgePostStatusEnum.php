<?php

namespace WiserWebSolutions\ChesCoGis\Bridges;

/**
 * Chester County's `POST_STATUS` field — whether the bridge is open, closed, or posted (load-restricted).
 */
enum BridgePostStatusEnum: string
{
    case Open = 'OPEN';
    case Closed = 'CLOSED';
    case Posted = 'POSTED';

    public function description(): string
    {
        return match ($this) {
            self::Open => 'Open, no restriction',
            self::Closed => 'Closed to all traffic',
            self::Posted => 'Posted for a load-capacity restriction',
        };
    }
}
