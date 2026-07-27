<?php

declare(strict_types=1);

namespace App\Application\Event;

abstract class GameEvent
{
    /** @param list<string|list<string>> $battleLogs */
    public function __construct(
        public string $gameId,
        public string $battleId,
        public string $battleStatus,
        public array $battleLogs
    ) {
    }
}
