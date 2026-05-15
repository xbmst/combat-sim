<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Service\DamageCalculatorInterface;

class Battle
{
    public function __construct(
        private readonly string $battleId,
        private readonly Warrior $character,
        private Warrior $opponent,
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
        $result = $damageCalculator->calculateStrike($this->character, $this->opponent);

        $this->roundLogs[] = $result->logs;
    }

    public function getBattleId(): string
    {
        return $this->battleId;
    }

    public function getCharacter(): Warrior
    {
        return $this->character;
    }

    public function getOpponent(): Warrior
    {
        return $this->opponent;
    }

    public function getCurrentRound(): int
    {
        return $this->currentRound;
    }

    public function getRoundLogs(): array
    {
        return $this->roundLogs;
    }

    public function setupNextBattle(Warrior $newOpponentStats): void
    {
        // TODO: wrappers
        $this->currentRound++;
        $this->roundLogs = [];
        $this->opponent = $newOpponentStats;
    }

    public function isCharacterDead(): bool
    {
        return $this->character->stats->currentHp <= 0;
    }

    public function isOpponentDead(): bool
    {
        return $this->opponent->stats->currentHp <= 0;
    }

    public function isAllRoundsComplete(): bool
    {
        return $this->isOpponentDead()
            && ($this->currentRound >= $this->targetBattles);
    }
}
