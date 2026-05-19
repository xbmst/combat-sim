<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Event\GameStartedEvent;
use App\Domain\Model\Battle;
use App\Domain\Model\Stats;
use App\Domain\Model\Warrior;
use App\Domain\Port\ActiveBattleRepositoryInterface;
use App\Domain\Port\EventBusInterface;
use App\Domain\Port\GameConfigRepositoryInterface;
use App\Domain\Service\OpponentBuilder;
use App\Domain\ValueObject\CharacterLoadout;
use App\Domain\ValueObject\GameLengthSettings;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
readonly class StartGameCommandHandler
{
    public function __construct(
        private GameConfigRepositoryInterface $configRepository,
        private ActiveBattleRepositoryInterface $activeBattleRepository,
        private OpponentBuilder $opponentBuilder,
        private EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(StartGameCommand $command): void
    {
        $settings = new GameLengthSettings($command->targetBattles);

        $characterClass = $this->configRepository->getClassByName($command->characterClassId);
        $items = $this->configRepository->getItemsByIds($command->equippedItemsIds);

        $characterLoadout = new CharacterLoadout($characterClass, $items);

        $character = new Warrior($characterLoadout->gameClass->name, Stats::buildFromClass($characterLoadout->gameClass), $characterLoadout->items);

        $battle = new Battle(
            $command->battleId ?? Uuid::v7()->toRfc4122(),
            $character,
            $this->opponentBuilder->build(),
            $settings->targetBattles
        );

        $this->activeBattleRepository->save($battle);

        $this->eventBus->dispatch(
            new GameStartedEvent(
                Uuid::v7()->toRfc4122(),
                $battle->getBattleId(),
                $battle->getCharacter()->name,
                $this->configRepository->getItemNamesFromItems($items),
            )
        );
    }
}
