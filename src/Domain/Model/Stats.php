<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\ValueObject\GameClass;
use App\Domain\ValueObject\Item;

class Stats
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

    /**
     * @param Item[] $items
     */
    public static function buildFromClassAndItems(GameClass $class, array $items): self
    {
        $hp = $class->baseHp;
        $attack = $class->baseAttack;
        $defense = $class->baseDefense;
        $agility = $class->baseAgility;

        foreach ($items as $item) {
            $hp += $item->modifierHp;
            $attack += $item->modifierAttack;
            $defense += $item->modifierDefense;
            $agility += $item->modifierAgility;
        }

        return new self($hp, $hp, $attack, $defense, $agility);
    }
}
