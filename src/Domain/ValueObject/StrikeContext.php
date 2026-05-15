<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

class StrikeContext
{
    public function __construct(
        private array $logs = [],
    ) {
    }

    public function addLog(string $message): void
    {
        $this->logs[] = $message;
    }
}
