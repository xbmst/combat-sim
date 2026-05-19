<?php

declare(strict_types=1);

namespace App\Application\Query;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetGameLogHandler
{
    public function __construct(private Connection $db)
    {
    }

    public function __invoke(string $gameId): array
    {
        $sql = 'SELECT id, game_id as gameId, battle_id as battleId, status, round_logs as logs FROM game_logs WHERE game_id = :id ORDER BY id DESC';

        $result = $this->db->fetchAssociative($sql, ['id' => $gameId]);

        if (!$result) {
            throw new NotFoundHttpException('Game log not found.');
        }

        return json_decode($result['logs'], true, 512, JSON_THROW_ON_ERROR);
    }
}
