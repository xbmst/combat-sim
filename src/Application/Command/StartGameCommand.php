<?php

declare(strict_types=1);

namespace App\Application\Command;

use OpenApi\Attributes as OA;

class StartGameCommand
{
    public function __construct(
        #[OA\Property(description: 'The unique identifier of the game.', type: 'string', example: '123e4567-e89b-12d3-a456-426614174000')]
        public string $battleId,
        public string $playerId,
        public string $heroClassId,
        public array $equippedItemsIds,
        public int $targetBattles,
    ) {
    }
}
