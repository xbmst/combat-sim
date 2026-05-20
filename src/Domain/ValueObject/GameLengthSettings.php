<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\InvalidGameSettingsException;

readonly class GameLengthSettings
{
    private const int MIN_BATTLES = 1;
    public const int MAX_BATTLES = 9000;

    public function __construct(
        public int $targetBattles,
    )
    {
        if ($targetBattles < self::MIN_BATTLES) {
            throw new InvalidGameSettingsException(sprintf('You must choose at least %d opponent.', self::MIN_BATTLES));
        }
    }
}
