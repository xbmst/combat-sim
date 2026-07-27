<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Port\GameLogRepositoryInterface;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DoctrineGameLogRepository implements GameLogRepositoryInterface
{
    public function __construct(private Connection $connection)
    {
    }

    public function getLogByGameId(string $gameId): string
    {
        $sql = 'SELECT id, game_id as gameId, battle_id as battleId, status, round_logs as logs FROM game_logs WHERE game_id = :id ORDER BY id DESC';

        $result = $this->connection->fetchAssociative($sql, ['id' => $gameId]);

        if (!$result) {
            throw new NotFoundHttpException('Game log not found.');
        }

        $logs = $result['logs'] ?? null;

        if (!is_string($logs)) {
            throw new NotFoundHttpException('Game log data is invalid.');
        }

        return $logs;
    }
}
