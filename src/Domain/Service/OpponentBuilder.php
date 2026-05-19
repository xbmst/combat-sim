<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Model\Stats;
use App\Domain\Model\Warrior;
use App\Domain\Port\GameConfigRepositoryInterface;

class OpponentBuilder
{
    public function __construct(private GameConfigRepositoryInterface $configRepository)
    {
    }

    public function build(): Warrior
    {
        $baseClass = $this->configRepository->getRandomOpponentClass();

        $items = $this->configRepository->getRandomItems();

        return new Warrior(
            'The ' . $baseClass->name,
            Stats::buildFromClass($baseClass),
            $items,
        );
    }
}
