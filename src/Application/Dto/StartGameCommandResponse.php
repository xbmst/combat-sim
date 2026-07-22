<?php

declare(strict_types=1);

namespace App\Application\Dto;

class StartGameCommandResponse
{
    public function __construct(
        public string $gameId,
        public string $opponentName,
    ) {
    }
}
