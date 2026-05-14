<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;

class InvalidLoadoutException extends Exception
{
    public function __construct(string $message = 'Invalid loadout')
    {
        parent::__construct($message, 400);
    }
}
