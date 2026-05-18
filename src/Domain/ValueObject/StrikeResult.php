<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

readonly class StrikeResult
{
    public function __construct(
        public int $damageToDeal,
        public array $logs = [],
    ) {
    }
}
