<?php

declare(strict_types=1);

namespace App\Infrastructure\Messaging;

use App\Application\Event\GameEvent;
use App\Infrastructure\Persistence\Doctrine\Entity\GameLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GameEventHandler
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function __invoke(GameEvent $event): void
    {
        $this->em->persist(new GameLog(
            $event->gameId,
            $event->battleId,
            $event->battleStatus,
            $event->battleLogs,
        ));

        $this->em->flush();
    }
}
