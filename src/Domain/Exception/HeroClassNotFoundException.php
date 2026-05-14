<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;

class HeroClassNotFoundException extends Exception
{
    public function __construct(string $message = 'Hero Class not found')
    {
        parent::__construct($message, 404);
    }
}
