<?php

declare(strict_types=1);

namespace App\Domain\Port;

interface GameLogRepositoryInterface
{
    public function getLogByGameId(string $gameId): string;
}
