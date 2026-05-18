<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\ValueObject\Item;

class Warrior
{
    public function __construct(
        public string $name,
        public Stats $stats,
        /** @var Item[] $items */
        public array $items,
    ) {
    }

    public function takeDamage(int $amount): self
    {
        return new self(
            $this->name,
            $this->stats->takeDamage($amount),
            $this->items,
        );
    }

    public function resetHealth(): self
    {
        return new self(
            $this->name,
            $this->stats->resetHealth(),
            $this->items,
        );
    }
}
