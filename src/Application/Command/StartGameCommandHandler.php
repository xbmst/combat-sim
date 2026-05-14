<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Model\Battle;
use App\Domain\Model\Stats;
use App\Domain\Port\ActiveBattleRepositoryInterface;
use App\Domain\Port\GameConfigRepositoryInterface;
use App\Domain\ValueObject\GameLengthSettings;
use App\Domain\ValueObject\HeroLoadout;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class StartGameCommandHandler
{
    public function __construct(
        private GameConfigRepositoryInterface $configRepository,
        private ActiveBattleRepositoryInterface $activeBattleRepository,
    ) {
    }

    public function __invoke(StartGameCommand $command): void
    {
        $settings = new GameLengthSettings($command->targetBattles);

        $heroClass = $this->configRepository->getClassByName($command->heroClassId);
        $items = $this->configRepository->getItemsByIds($command->equippedItemsIds);

        $heroLoadout = new HeroLoadout($heroClass, $items);

        $heroStats = Stats::buildFromClassAndItems($heroLoadout->gameClass, $heroLoadout->items);
        $enemyStats = Stats::buildFromClassAndItems($this->configRepository->getRandomEnemyClass(), []);

        $battle = new Battle(
            $command->battleId,
            $heroStats,
            $enemyStats,
            $settings->targetBattles
        );

        $this->activeBattleRepository->save($battle);
    }
}
