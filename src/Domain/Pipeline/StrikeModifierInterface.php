<?php

declare(strict_types=1);

namespace App\Domain\Pipeline;

use App\Domain\ValueObject\StrikeContext;

interface StrikeModifierInterface
{
    public function apply(StrikeContext $context): StrikeContext;
}
