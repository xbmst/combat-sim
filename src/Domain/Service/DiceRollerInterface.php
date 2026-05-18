<?php

declare(strict_types=1);

namespace App\Domain\Service;

interface DiceRollerInterface
{
    public function roll(int $min, int $max): int;
}
