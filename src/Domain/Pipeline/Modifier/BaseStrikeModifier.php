<?php

declare(strict_types=1);

namespace App\Domain\Pipeline\Modifier;

use App\Domain\Pipeline\StrikeModifierInterface;
use App\Domain\ValueObject\StrikeContext;

class BaseStrikeModifier implements StrikeModifierInterface
{
    public function apply(StrikeContext $context): StrikeContext
    {
        $totalAttack = $context->attackerStats->attack;
        $totalDefense = $context->defenderStats->defense;

        $damage = max(0, $totalAttack - $totalDefense);

        return $context
            ->withDamage($damage)
            ->withLog(sprintf('[Math: %d attack vs %d defense]', $totalAttack, $totalDefense));
    }
}
