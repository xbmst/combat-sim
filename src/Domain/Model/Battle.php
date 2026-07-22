<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Service\DamageCalculator;
use App\Domain\Service\DiceRoller;
use App\Domain\Service\TurnPicker;
use App\Domain\ValueObject\GameLengthSettings;
use App\Domain\ValueObject\StrikeResult;

class Battle
{
    public function __construct(
        private readonly string $battleId,
        private readonly string $gameId,
        private Warrior $character,
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

    public function execute(DamageCalculator $damageCalculator, DiceRoller $dice, TurnPicker $turnPicker): void
    {
        // TODO: refactor to pipeline?
        [$attacker, $defender] = $turnPicker->pick($this->character, $this->opponent);
        $this->roundLogs[] = $attacker->name.' starts first!';

        $strikeResult = $damageCalculator->calculateStrike($attacker, $defender);

        if (!$this->isAttackDodged($dice, $strikeResult->defenderStats)) {
            $this->strike($attacker, $defender, $strikeResult);

            if ($this->isOpponentDead()) {
                return;
            }
        } else {
            $this->roundLogs[] = sprintf('%s tries to hit but %s dodges!', $attacker->name, $defender->name);
        }

        $counterStrikeResult = $damageCalculator->calculateStrike($defender, $attacker);

        if (!$this->isAttackDodged($dice, $counterStrikeResult->defenderStats)) {
            $this->strike($defender, $attacker, $counterStrikeResult);
        } else {
            $this->roundLogs[] = sprintf('%s tries to hit but %s dodges!', $defender->name, $attacker->name);
        }
    }

    private function strike(Warrior $attacker, Warrior $defender, StrikeResult $result): void
    {
        $this->roundLogs[] = $result->logs;

        $defender->stats = ($defender->takeDamage($result->damageToDeal))->stats;

        $this->roundLogs[] = sprintf('%s hits %s for %d damage.', $attacker->name, $defender->name, $result->damageToDeal);
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

    public function setupNextBattle(Warrior $newOpponent): void
    {
        // TODO: wrappers
        $this->currentRound++;
        $this->roundLogs = [];
        $this->opponent = $newOpponent;

        $this->character = $this->character->resetHealth();
        $this->addLog('Character Health has been restored');
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
        return (
            $this->isOpponentDead()
                && ($this->currentRound >= $this->targetBattles)
            )
            || $this->currentRound > GameLengthSettings::MAX_BATTLES;
    }

    public function isAttackDodged(DiceRoller $dice, Stats $defenderStats): bool
    {
        $diceResult = $dice->roll();

        $this->addLog(sprintf('[Dice: %d vs %d Warrior]', $diceResult, $defenderStats->agility));

        return $diceResult <= $defenderStats->agility;
    }

    public function getGameId(): string
    {
        return $this->gameId;
    }

    private function addLog(string $message): void
    {
        $this->roundLogs[] = $message;
    }
}
