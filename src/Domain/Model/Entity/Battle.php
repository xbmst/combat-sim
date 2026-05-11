<?php

declare(strict_types=1);

namespace App\Domain\Model\Entity;

use App\Domain\Model\Stats;

class Battle
{
    private array $roundLogs = [];

    public function __construct(
        private string $battleId,
        private readonly Stats $heroStats,
        private Stats $enemyStats,
    ) {
    }

    public function execute(): void
    {
        $damage = max(0, $this->heroStats->attack - $this->enemyStats->defense);
        $this->enemyStats = $this->enemyStats->takeDamage($damage);

        $this->roundLogs[] = "Hero hit Enemy for $damage damage!";
    }
}
