<?php

declare(strict_types=1);

namespace App\Application\Command;

class StartGameCommand
{
    public function __construct(
        public string $battleId,
        public string $playerId,
        public string $heroClassId,
        public array $equippedItemsIds,
    ){}
}
