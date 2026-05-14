<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;

class BattleNotFoundException extends Exception
{
    public function __construct(string $message = 'Battle not found')
    {
        parent::__construct($message, 404);
    }
}
