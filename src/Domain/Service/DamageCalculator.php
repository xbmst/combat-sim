<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Model\Warrior;
use App\Domain\Pipeline\StrikeModifierInterface;
use App\Domain\ValueObject\StrikeContext;
use App\Domain\ValueObject\StrikeResult;

readonly class DamageCalculator implements DamageCalculatorInterface
{
    /**
     * @param StrikeModifierInterface[] $pipeline
     */
    public function __construct(private array $pipeline)
    {
    }

    public function calculateStrike(Warrior $attacker, Warrior $defender): StrikeResult
    {
        $context = new StrikeContext($attacker, $defender, $attacker->stats->attack);

        foreach ($this->pipeline as $modifier) {
            $context = $modifier->apply($context);
        }

        return new StrikeResult($context->damageAmount, $context->logs);
    }
}
