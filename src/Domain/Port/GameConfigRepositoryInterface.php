<?php

declare(strict_types=1);

namespace App\Domain\Port;

use App\Domain\ValueObject\CharacterLoadout;
use App\Domain\ValueObject\GameClass;
use App\Domain\ValueObject\Item;

interface GameConfigRepositoryInterface
{
    public function getClassById(string $id): GameClass;

    /**
     * @param list<string> $ids
     *
     * @return list<Item>
     */
    public function getItemsByIds(array $ids): array;

    public function getRandomOpponentClass(): GameClass;

    public function getClassByName(string $name): GameClass;

    /**
     * @return list<Item>
     */
    public function getRandomItems(int $limit = CharacterLoadout::MAX_ITEMS): array;

    /**
     * @param list<Item> $items
     *
     * @return list<string>
     */
    public function getItemNamesFromItems(array $items): array;

    /** @return list<array<string, mixed>> */
    public function getAllClasses(): array;

    /** @return list<array<string, mixed>> */
    public function getAllItems(): array;
}
