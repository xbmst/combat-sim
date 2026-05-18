<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

class Item
{
    public function __construct(
        public string $id,
        public string $name,
        public string $category,
        public int $modifierAttack = 0,
        public int $modifierDefense = 0,
        public int $modifierAgility = 0,
    ) {
    }
}
