<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Model\Warrior;
use App\Domain\ValueObject\StrikeResult;

interface DamageCalculatorInterface
{
    public function calculateStrike(Warrior $attacker, Warrior $defender): StrikeResult;
}
