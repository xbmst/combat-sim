<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\ValueObject\GameClass;
use App\Domain\ValueObject\Item;

readonly class Stats
{
    public function __construct(
        public int $maxHp,
        public int $currentHp,
        public int $attack,
        public int $defense,
        public int $agility,
    ) {
    }

    public function takeDamage(int $amount): self
    {
        return new self(
            $this->maxHp,
            max(0, $this->currentHp - $amount),
            $this->attack,
            $this->defense,
            $this->agility
        );
    }

    public static function buildFromClass(GameClass $class): self
    {
        return new self(
            $class->baseHp,
            $class->baseHp,
            $class->baseAttack,
            $class->baseDefense,
            $class->baseAgility,
        );
    }

    public function resetHealth(): self
    {
        return new self(
            $this->maxHp,
            $this->maxHp,
            $this->attack,
            $this->defense,
            $this->agility
        );
    }

    public function increase(int $attack, int $defense, int $agility, int $maxHp = 0): self
    {
        return new self(
            $this->maxHp + $maxHp,
            $this->currentHp,
            $this->attack + $attack,
            $this->defense + $defense,
            $this->agility + $agility
        );
    }
}
