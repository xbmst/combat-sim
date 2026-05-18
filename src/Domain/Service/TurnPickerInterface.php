<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Model\Warrior;

interface TurnPickerInterface
{
    /**
     * @return Warrior[]
     */
    public function pick(Warrior $character, Warrior $opponent): array;
}
