<?php

declare(strict_types=1);

namespace App\Domain\Port;

use App\Domain\Model\ValueObject\GameClass;
use App\Domain\Model\ValueObject\Item;

interface GameConfigRepositoryInterface
{
    public function getClassById(string $id): GameClass;

    /**
     * @return Item[]
     */
    public function getItemsByIds(array $ids): array;

    public function getRandomEnemyClass(): GameClass;
}
