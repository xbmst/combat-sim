<?php

declare(strict_types=1);

namespace App\Domain\Pipeline\Modifier;

use App\Domain\Pipeline\StrikeModifierInterface;
use App\Domain\ValueObject\StrikeContext;

class BaseDefenseModifier implements StrikeModifierInterface
{
    public function apply(StrikeContext $context): void
    {
    }
}
