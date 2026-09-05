<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Model\GameLog;
use App\Domain\Port\GameLogRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Entity\GameLog as GameLogEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DoctrineGameLogRepository implements GameLogRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /** @return list<GameLog> */
    public function getLogsByGameId(string $gameId): array
    {
        $entities = $this->entityManager
            ->getRepository(GameLogEntity::class)
            ->findBy(['gameId' => $gameId], ['id' => 'ASC']);

        if ($entities === []) {
            throw new NotFoundHttpException('Game log not found.');
        }

        return array_map(
            static fn (GameLogEntity $entity): GameLog => new GameLog(
                $entity->id,
                $entity->gameId,
                $entity->battleId,
                $entity->status,
                $entity->roundLogs,
            ),
            $entities,
        );
    }
}
