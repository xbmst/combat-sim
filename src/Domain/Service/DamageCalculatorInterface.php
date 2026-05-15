<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Model\Stats;
use App\Domain\ValueObject\StrikeResult;

interface DamageCalculatorInterface
{
    public function calculateStrike(Stats $attackerStats, Stats $defenderStats): StrikeResult;
}
