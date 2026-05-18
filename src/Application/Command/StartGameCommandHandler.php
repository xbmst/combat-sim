<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Model\Battle;
use App\Domain\Model\Stats;
use App\Domain\Model\Warrior;
use App\Domain\Port\ActiveBattleRepositoryInterface;
use App\Domain\Port\GameConfigRepositoryInterface;
use App\Domain\Service\OpponentBuilder;
use App\Domain\ValueObject\GameLengthSettings;
use App\Domain\ValueObject\CharacterLoadout;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class StartGameCommandHandler
{
    public function __construct(
        private GameConfigRepositoryInterface $configRepository,
        private ActiveBattleRepositoryInterface $activeBattleRepository,
        private OpponentBuilder $opponentBuilder,
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
            $command->battleId,
            $character,
            $this->opponentBuilder->build(),
            $settings->targetBattles
        );

        $this->activeBattleRepository->save($battle);
    }
}
