<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Service\DamageCalculatorInterface;

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

    public function execute(DamageCalculatorInterface $damageCalculator): void
    {
        $result = $damageCalculator->calculateStrike($this->heroStats, $this->enemyStats);

        $this->roundLogs[] = $result->logs;
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

    public function isHeroDead(): bool
    {
        return $this->heroStats->currentHp <= 0;
    }

    public function isEnemyDead(): bool
    {
        return $this->enemyStats->currentHp <= 0;
    }

    public function isAllRoundsComplete(): bool
    {
        return $this->isEnemyDead()
            && ($this->currentRound >= $this->targetBattles);
    }
}
