<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Model\Stats;
use App\Domain\Model\Warrior;

readonly class StrikeContext
{
    public function __construct(
        public Warrior $attacker,
        public Warrior $defender,
        public Stats $attackerStats,
        public Stats $defenderStats,
        public int $damageAmount = 0,
        public array $logs = [],
    ) {
    }

    public static function fromWarriors(Warrior $attacker, Warrior $defender): self
    {
        return new self(
            $attacker,
            $defender,
            $attacker->stats,
            $defender->stats,
        );
    }

    public function withAttackerStats(Stats $stats): self
    {
        return new self(
            $this->attacker,
            $this->defender,
            $stats,
            $this->defenderStats,
            $this->damageAmount,
            $this->logs,
        );
    }

    public function withDefenderStats(Stats $stats): self
    {
        return new self(
            $this->attacker,
            $this->defender,
            $this->attackerStats,
            $stats,
            $this->damageAmount,
            $this->logs,
        );
    }

    public function withLog(string $message): self
    {
        return new self(
            $this->attacker,
            $this->defender,
            $this->attackerStats,
            $this->defenderStats,
            $this->damageAmount,
            array_merge($this->logs, [$message]),
        );
    }

    public function withDamage(int $damage): self
    {
        return new self(
            $this->attacker,
            $this->defender,
            $this->attackerStats,
            $this->defenderStats,
            $damage,
            $this->logs,
        );
    }
}
