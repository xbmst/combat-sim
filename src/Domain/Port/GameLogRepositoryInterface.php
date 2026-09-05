<?php

declare(strict_types=1);

namespace App\Domain\Port;

use App\Domain\Model\GameLog;

interface GameLogRepositoryInterface
{
    /** @return list<GameLog> */
    public function getLogsByGameId(string $gameId): array;
}
