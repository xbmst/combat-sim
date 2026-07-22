<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Model\Warrior;
use App\Domain\Pipeline\StrikeModifierInterface;
use App\Domain\ValueObject\StrikeContext;
use App\Domain\ValueObject\StrikeResult;

readonly class DamageCalculator
{
    /**
     * @param StrikeModifierInterface[] $pipeline
     */
    public function __construct(private array $pipeline)
    {
    }

    public function calculateStrike(Warrior $attacker, Warrior $defender): StrikeResult
    {
        $context = StrikeContext::fromWarriors($attacker, $defender);

        foreach ($this->pipeline as $modifier) {
            $context = $modifier->apply($context);
        }

        return new StrikeResult(
            $context->damageAmount,
            $context->attackerStats,
            $context->defenderStats,
            $context->logs,
        );
    }
}
