<?php

declare(strict_types=1);

namespace App\Application\Dto;

use App\Domain\ValueObject\BattleStatus;

use OpenApi\Attributes as OA;

readonly class PlayRoundCommandResponse
{
    public function __construct(
        #[OA\Property(description: 'Battle status', type: 'string', example: 'game_over')]
        public BattleStatus $status,
        public array $logs,
        public int $characterCurrentHp,
        public int $opponentCurrentHp,
        public string $opponentName,
    ) {
    }
}
