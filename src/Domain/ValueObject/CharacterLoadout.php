<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\InvalidLoadoutException;

readonly class CharacterLoadout
{
    public const int MAX_ITEMS = 3;

    /**
     * @param Item[] $items
     *
     * @throws InvalidLoadoutException
     */
    public function __construct(
        public GameClass $gameClass,
        public array $items
    ) {
        $this->validateItems($items);
    }

    private function validateItems(array $items): void
    {
        if (count($items) > self::MAX_ITEMS) {
            throw new InvalidLoadoutException(sprintf('You cannot equip more than %d items.', self::MAX_ITEMS));
        }
    }
}
