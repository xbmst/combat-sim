<?php

declare(strict_types=1);

namespace App\Application\Event;

use App\Domain\ValueObject\BattleStatus;

class GameOverEvent extends GameEvent
{
    public function __construct(string $gameId, string $battleId, array $battleLogs) {
        parent::__construct($gameId, $battleId, BattleStatus::GAME_OVER->value, $battleLogs);
    }
}
