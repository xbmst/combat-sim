<?php

declare(strict_types=1);

namespace App\Infrastructure\Messaging;

use App\Domain\Port\EventBusInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerEventBus implements EventBusInterface
{
    public function __construct(private MessageBusInterface $eventBus)
    {
    }

    public function dispatch(object $event): void
    {
        $this->eventBus->dispatch($event);
    }
}
