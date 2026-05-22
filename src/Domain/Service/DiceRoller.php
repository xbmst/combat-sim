<?php

declare(strict_types=1);

namespace App\Domain\Service;

class DiceRoller
{
    public function roll(int $min = 1, int $max = 100): int
    {
        return random_int($min, $max);
    }
}
