<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;

class ItemClassNotFoundException extends Exception
{
    public function __construct(string $message = 'Item Class not found')
    {
        parent::__construct($message, 404);
    }
}
