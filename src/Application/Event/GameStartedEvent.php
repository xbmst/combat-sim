<?php

declare(strict_types=1);

namespace App\Application\Event;

use App\Domain\ValueObject\BattleStatus;

class GameStartedEvent extends GameEvent
{
    public function __construct(
        public string $gameId,
        public string $battleId,
        public string $characterClassName,
        public array $equippedItemsNames,
    ) {
        parent::__construct($gameId, $battleId, BattleStatus::NEXT_TURN->value, []);
    }
}
