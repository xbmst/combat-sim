<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Model\Stats;

readonly class StrikeResult
{
    public function __construct(
        public int $damageToDeal,
        public Stats $attackerStats,
        public Stats $defenderStats,
        public array $logs = [],
    ) {
    }
}
