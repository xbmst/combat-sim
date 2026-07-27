<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Model\Warrior;

class TurnPicker
{
    /** @return array{Warrior, Warrior} */
    public function pick(Warrior $character, Warrior $opponent): array
    {
        if ($character->stats->agility >= $opponent->stats->agility) {
            return [$character, $opponent];
        }

        return [$opponent, $character];
    }
}
