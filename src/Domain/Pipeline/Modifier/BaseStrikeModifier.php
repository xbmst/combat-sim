<?php

declare(strict_types=1);

namespace App\Domain\Pipeline\Modifier;

use App\Domain\Pipeline\StrikeModifierInterface;
use App\Domain\ValueObject\StrikeContext;

class BaseStrikeModifier implements StrikeModifierInterface
{
    public function apply(StrikeContext $context): StrikeContext
    {
        return $context->withDamage($this->calculateDamage($context));
    }

    private function calculateDamage(StrikeContext $context): int
    {
        $totalAttack = $context->attacker->stats->attack;
        $totalDefense = $context->defender->stats->defense;

        $rawDamage = $totalAttack - $totalDefense;

        $finalDamage = max(0, $rawDamage);

        $context->withLog(sprintf('[Math: %d attack vs %d defense]', $totalAttack, $totalDefense));

        return $finalDamage;
    }
}
