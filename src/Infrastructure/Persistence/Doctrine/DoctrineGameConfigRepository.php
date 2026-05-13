<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Model\ValueObject\GameClass;
use App\Domain\Port\GameConfigRepositoryInterface;

class DoctrineGameConfigRepository implements GameConfigRepositoryInterface
{

    public function getClassById(string $id): GameClass
    {
        // TODO: Implement getClassById() method.
    }

    public function getItemsByIds(array $ids): array
    {
        // TODO: Implement getItemsByIds() method.
    }

    public function getRandomEnemyClass(): GameClass
    {
        // TODO: Implement getRandomEnemyClass() method.
    }
}
