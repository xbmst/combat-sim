<?php

declare(strict_types=1);

namespace App\Domain\Model;

readonly class GameLog
{
    /** @param list<string|list<string>> $roundLogs */
    public function __construct(
        public string $id,
        public string $gameId,
        public string $battleId,
        public string $status,
        public array $roundLogs,
    ) {
    }
}
