<?php

declare(strict_types=1);

namespace App\Domain\Pipeline\Modifier;

use App\Domain\Pipeline\StrikeModifierInterface;
use App\Domain\ValueObject\StrikeContext;

readonly class DefenderItemStatsModifier implements StrikeModifierInterface
{
    public function apply(StrikeContext $context): StrikeContext
    {
        $stats = $context->defenderStats;

        foreach ($context->defender->items as $item) {
            $stats = $stats->increase(
                $item->modifierAttack ?? $item['modifierAttack'],
                $item->modifierDefense ?? $item['modifierDefense'],
                $item->modifierAgility ?? $item['modifierAgility'],
            );
        }

        return $context->withDefenderStats($stats);
    }
}
