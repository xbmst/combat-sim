<?php

declare(strict_types=1);

namespace App\Domain\Port;

use App\Domain\ValueObject\GameClass;
use App\Domain\ValueObject\Item;

interface GameConfigRepositoryInterface
{
    public function getClassById(string $id): GameClass;

    /**
     * @return Item[]
     */
    public function getItemsByIds(array $ids): array;

    public function getRandomOpponentClass(): GameClass;

    public function getClassByName(string $name): GameClass;
}
