<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;

class CharacterClassNotFoundException extends Exception
{
    public function __construct(string $message = 'Character Class not found')
    {
        parent::__construct($message, 404);
    }
}
