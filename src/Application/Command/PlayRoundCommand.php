<?php

declare(strict_types=1);

namespace App\Application\Command;

readonly class PlayRoundCommand
{
    public function __construct(public string $battleId)
    {
    }
}
