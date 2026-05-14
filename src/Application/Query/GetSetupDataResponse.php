<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Domain\ValueObject\GameClass;
use App\Domain\ValueObject\Item;

readonly class GetSetupDataResponse
{
    public function __construct(
        /** @var GameClass[] $classes */
        public array $classes,
        /** @var Item[] $items */
        public array $items,
        public array $rules = [],
    ) {
    }
}
