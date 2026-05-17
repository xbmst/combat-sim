<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Model\Warrior;

readonly class StrikeContext
{
    public function __construct(
        public Warrior $attacker,
        public Warrior $defender,
        public int $damageAmount,
        public array $logs = [],
    ) {
    }

    public function withLog(string $message): self
    {
        return new self($this->attacker, $this->defender, $this->damageAmount, array_merge($this->logs, [$message]));
    }

    public function withDamage(int $damage): self
    {
        return new self($this->attacker, $this->defender, $damage, $this->logs);
    }
}
