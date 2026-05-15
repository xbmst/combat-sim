<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Pipeline\StrikeModifierInterface;

class Warrior
{
    public function __construct(
        public Stats $stats,
        /** @var StrikeModifierInterface[] $strikeModifiers */
        public array $strikeModifiers = [],
    ) {
    }

    public function takeDamage(int $amount): self
    {
        return new self(
            $this->stats->takeDamage($amount),
            $this->strikeModifiers,
        );
    }
}
