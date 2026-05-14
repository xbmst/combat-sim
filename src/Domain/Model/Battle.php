<?php

declare(strict_types=1);

namespace App\Domain\Model;

class Battle
{
    public function __construct(
        private readonly string $battleId,
        private readonly Stats $heroStats,
        private Stats $enemyStats,
        private readonly int $targetBattles,
        private int $currentRound = 1,
        private array $roundLogs = [],
    ) {
    }

    public function getTargetBattles(): int
    {
        return $this->targetBattles;
    }

    public function execute(): void
    {
        $damage = max(0, $this->heroStats->attack - $this->enemyStats->defense); // TODO: damage calculator service
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

    public function setupNextBattle(Stats $newEnemyStats): void
    {
        // TODO: wrappers
        $this->currentRound++;
        $this->roundLogs = [];
        $this->enemyStats = $newEnemyStats;
    }
}
