<?php

declare(strict_types=1);

namespace App\Domain\Model;

class Warrior
{
    public function __construct(
        public string $name,
        public Stats $stats,
    ) {
    }

    public function takeDamage(int $amount): self
    {
        return new self(
            $this->name,
            $this->stats->takeDamage($amount),
        );
    }

    public function resetHealth(): self
    {
        return new self(
            $this->name,
            $this->stats->resetHealth(),
        );
    }
}
