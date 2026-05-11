<?php

declare(strict_types=1);

namespace App\Domain\Model\ValueObject;

class GameClass
{
    public function __construct(
        public string $id,
        public int $baseHp,
        public int $baseAttack,
        public int $baseDefense,
        public int $baseAgility,
    ) {
    }
}
