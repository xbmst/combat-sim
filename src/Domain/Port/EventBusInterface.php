<?php

declare(strict_types=1);

namespace App\Domain\Port;

interface EventBusInterface
{
    public function dispatch(object $event): void;
}
