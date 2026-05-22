<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Model\Warrior;

class TurnPicker
{
    public function pick(Warrior $character, Warrior $opponent): array
    {
        $adversaries = [$character, $opponent];

        usort($adversaries, static function ($a, $b) {
            return $b->stats->agility <=> $a->stats->agility;
        });

        return $adversaries;
    }
}
