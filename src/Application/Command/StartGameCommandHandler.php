<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Model\Entity\Battle;
use App\Domain\Model\Stats;
use App\Domain\Model\ValueObject\HeroLoadout;
use App\Domain\Port\ActiveBattleRepositoryInterface;
use App\Domain\Port\GameConfigRepositoryInterface;
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
        $heroClass = $this->configRepository->getClassByName($command->heroClassId);
        $items = $this->configRepository->getItemsByIds($command->equippedItemsIds);

        $heroLoadout = new HeroLoadout($heroClass, $items);

        $heroStats = Stats::buildFromClassAndItems($heroLoadout->gameClass, $heroLoadout->items);
        $enemyStats = Stats::buildFromClassAndItems($this->configRepository->getRandomEnemyClass(), []);

        $battle = new Battle($command->battleId, $heroStats, $enemyStats);

        $this->activeBattleRepository->save($battle);
    }
}
