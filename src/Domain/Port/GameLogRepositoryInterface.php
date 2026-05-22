<?php

declare(strict_types=1);

namespace App\Domain\Port;

use App\Domain\ValueObject\Item;

interface GameLogRepositoryInterface
{
    public function getLogByGameId(string $gameId);
}
