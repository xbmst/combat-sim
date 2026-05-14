<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;

class InvalidGameSettingsException extends Exception
{
    public function __construct(string $message = 'Invalid game settings')
    {
        parent::__construct($message, 400);
    }
}
