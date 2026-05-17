<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

class GameClass
{
    public function __construct(
        public string $id,
        public string $name,
        public int $baseHp,
        public int $baseAttack,
        public int $baseDefense,
        public int $baseAgility,
    ) {
    }
}
