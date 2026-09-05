<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Domain\Model\GameLog;
use App\Domain\Port\GameLogRepositoryInterface;

readonly class GetGameLogsHandler
{
    public function __construct(private GameLogRepositoryInterface $gameLogRepository)
    {
    }

    /** @return list<GameLog> */
    public function __invoke(string $gameId): array
    {
        return $this->gameLogRepository->getLogsByGameId($gameId);
    }
}
