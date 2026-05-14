<?php

declare(strict_types=1);

namespace App\Domain\Model\Entity;

use App\Domain\Model\Stats;

class Battle
{
    public function __construct(
        private readonly string $battleId,
        private readonly Stats $heroStats,
        private Stats $enemyStats,
        private readonly int $currentRound = 1,
        private array $roundLogs = []
    ) {
    }

    public function execute(): void
    {
        $damage = max(0, $this->heroStats->attack - $this->enemyStats->defense);
        $this->enemyStats = $this->enemyStats->takeDamage($damage);

        $this->roundLogs[] = "Hero hit Enemy for $damage damage!";
    }

    public function getBattleId(): string
    {
        return $this->battleId;
    }

    public function getHeroStats(): Stats
    {
        return $this->heroStats;
    }

    public function getEnemyStats(): Stats
    {
        return $this->enemyStats;
    }

    public function getCurrentRound(): int
    {
        return $this->currentRound;
    }

    public function getRoundLogs(): array
    {
        return $this->roundLogs;
    }
}
