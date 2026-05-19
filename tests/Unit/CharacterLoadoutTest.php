<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Domain\Exception\InvalidLoadoutException;
use App\Domain\ValueObject\CharacterLoadout;
use App\Domain\ValueObject\GameClass;
use App\Domain\ValueObject\Item;
use PHPUnit\Framework\TestCase;

class CharacterLoadoutTest extends TestCase
{
    public function test_only_one_category_of_item_can_be_equipped(): void
    {
        $class = new GameClass('id', 'Ninja', 100, 10, 10, 10);
        $item1 = new Item('id1', 'Sword', 'weapon', 10, 0, 0);
        $item2 = new Item('id2', 'Dagger', 'weapon', 5, 0, 0); // Duplicate category

        $this->expectException(InvalidLoadoutException::class);
        $this->expectExceptionMessage('You cannot equip multiple items of the same category - weapon.');

        new CharacterLoadout($class, [$item1, $item2]);
    }

    public function test_max_items_equipped(): void
    {
        $class = new GameClass('id', 'Ninja', 100, 10, 10, 10);
        $item1 = new Item('id1', 'Sword', 'weapon', 10, 0, 0);
        $item2 = new Item('id2', 'Shield', 'shield', 5, 0, 0);
        $item3 = new Item('id2', 'Item3', 'gloves', 5, 0, 0);
        $item4 = new Item('id2', 'Item4', 'armor', 5, 0, 0);

        $this->expectException(InvalidLoadoutException::class);
        $this->expectExceptionMessage('You cannot equip more than 3 items.');
        new CharacterLoadout($class, [$item1, $item2, $item3, $item4]);
    }
}
