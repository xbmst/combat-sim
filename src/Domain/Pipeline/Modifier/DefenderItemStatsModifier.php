<?php

declare(strict_types=1);

namespace App\Domain\Pipeline\Modifier;

use App\Domain\Pipeline\StrikeModifierInterface;
use App\Domain\ValueObject\StrikeContext;

readonly class DefenderItemStatsModifier implements StrikeModifierInterface
{
    public function apply(StrikeContext $context): StrikeContext
    {
        foreach ($context->defender->items as $item) {
            $context->defender->stats = $context->defender->stats->increase(
                $item['modifierAttack'],
                $item['modifierDefense'],
                $item['modifierAgility'],
            );
        }

        return $context;
    }
}
