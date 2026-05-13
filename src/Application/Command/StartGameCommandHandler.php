<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Model\Entity\Battle;
use App\Domain\Model\Stats;
use App\Domain\Port\ActiveBattleRepositoryInterface;
use App\Domain\Port\GameConfigRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

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
        $heroClass = $this->configRepository->getClassById($command->heroClassId);
        $items = $this->configRepository->getItemsByIds($command->equippedItemsIds);

        $enemyClass = $this->configRepository->getRandomEnemyClass();

        $heroStats = Stats::buildFromClassAndItems($heroClass, $items);
        $enemyStats = Stats::buildFromClassAndItems($enemyClass, []);

        $battleId = Uuid::v7()->toRfc4122();

        $battle = new Battle($battleId, $heroStats, $enemyStats);

        $this->activeBattleRepository->save($battle);
    }
}
