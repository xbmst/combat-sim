<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Model\Warrior;
use App\Domain\ValueObject\StrikeContext;
use App\Domain\ValueObject\StrikeResult;

class DamageCalculator implements DamageCalculatorInterface
{
    public function calculateStrike(Warrior $attacker, Warrior $defender): StrikeResult
    {
        $context = new StrikeContext($attacker->stats->attack);

        foreach ($attacker->strikeModifiers as $modifier) {
            $modifier->apply($context);
        }

        $defender->takeDamage($context);

        return new StrikeResult($context->logs);
    }
}
