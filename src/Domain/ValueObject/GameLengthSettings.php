<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\InvalidGameSettingsException;

readonly class GameLengthSettings
{
    private const int MIN_BATTLES = 1;
    private const int MAX_BATTLES = 99;

    public function __construct(
        public int $targetBattles,
    )
    {
        if ($targetBattles < self::MIN_BATTLES || $targetBattles > self::MAX_BATTLES) {
            throw new InvalidGameSettingsException(sprintf('You must choose between %d and %d opponents.', self::MIN_BATTLES, self::MAX_BATTLES));
        }
    }
}
