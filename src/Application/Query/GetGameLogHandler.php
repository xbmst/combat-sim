<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Domain\Port\GameLogRepositoryInterface;

readonly class GetGameLogHandler
{
    public function __construct(private GameLogRepositoryInterface $gameLogRepository)
    {
    }

    public function __invoke(string $gameId): array
    {
        return json_decode($this->gameLogRepository->getLogByGameId($gameId), true, 512, JSON_THROW_ON_ERROR);
    }
}
