<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Pipeline\StrikeModifierInterface;
use App\Domain\ValueObject\StrikeContext;

class Warrior
{
    public function __construct(
        public Stats $stats,
        /** @var StrikeModifierInterface[] $strikeModifiers */
        public array $strikeModifiers = [],
    ) {
    }

    public function takeDamage(StrikeContext $context): self
    {
        return new self(
            $this->stats->takeDamage($context->damageAmount),
            $this->strikeModifiers,
        );
    }
}
