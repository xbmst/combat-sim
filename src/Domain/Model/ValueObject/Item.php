<?php

declare(strict_types=1);

namespace App\Domain\Model\ValueObject;

class Item
{
    public function __construct(
        public int $modifierHp,
        public int $modifierAttack,
        public int $modifierDefense,
        public int $modifierAgility,
    ) {
    }
}
