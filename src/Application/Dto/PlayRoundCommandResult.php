<?php

declare(strict_types=1);

namespace App\Application\Dto;

use App\Domain\ValueObject\BattleStatus;

readonly class PlayRoundCommandResult
{
    public function __construct(
        public BattleStatus $status,
        public array $logs,
        public int $characterCurrentHp,
        public int $opponentCurrentHp,
    ) {
    }
}
