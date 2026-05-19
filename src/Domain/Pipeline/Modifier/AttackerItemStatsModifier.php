<?php

declare(strict_types=1);

namespace App\Domain\Pipeline\Modifier;

use App\Domain\Pipeline\StrikeModifierInterface;
use App\Domain\ValueObject\StrikeContext;

readonly class AttackerItemStatsModifier implements StrikeModifierInterface
{
    public function apply(StrikeContext $context): StrikeContext
    {
        foreach ($context->attacker->items as $item) {
            $context->attacker->stats = $context->attacker->stats->increase(
                $item['modifierAttack'],
                $item['modifierDefense'],
                $item['modifierAgility'],
            );
        }

        return $context;
    }
}
