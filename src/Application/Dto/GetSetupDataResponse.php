<?php

declare(strict_types=1);

namespace App\Application\Dto;

readonly class GetSetupDataResponse
{
    /**
     * @param list<array<string, mixed>> $classes
     * @param list<array<string, mixed>> $items
     * @param array<string, int> $rules
     */
    public function __construct(
        public array $classes,
        public array $items,
        public array $rules = [],
    ) {
    }
}
