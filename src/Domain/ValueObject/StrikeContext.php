<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

class StrikeContext
{
    public function __construct(
        public int $damageAmount,
        public array $logs = [] {
            get => $this->logs;
            set(array $v) => $this->logs[] = $v;
        },
    ) {
    }

    public function addLog(string $message): void
    {
        $this->logs = array_merge($this->logs, [$message]);
    }
}
