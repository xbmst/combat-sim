<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\ValueObject\StrikeContext;

class Warrior
{
    public function __construct(
        public string $name,
        public Stats $stats,
    ) {
    }

    public function takeDamage(StrikeContext $context): self
    {
        return new self(
            $this->name,
            $this->stats->takeDamage($context->damageAmount),
        );
    }
}
