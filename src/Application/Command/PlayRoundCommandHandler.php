<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Model\Stats;
use App\Domain\Port\ActiveBattleRepositoryInterface;
use App\Domain\Port\GameConfigRepositoryInterface;
use App\Domain\Service\DamageCalculatorInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class PlayRoundCommandHandler
{

    public function __construct(
        private ActiveBattleRepositoryInterface $battleRepository,
        private GameConfigRepositoryInterface $configRepository,
        private DamageCalculatorInterface $damageCalculator,
    ) {
    }

    public function __invoke(PlayRoundCommand $command): void
    {
        $battle = $this->battleRepository->findById($command->battleId);

        $battle->execute($this->damageCalculator);

        $newEnemyClass = $this->configRepository->getRandomEnemyClass();
        $newEnemyStats = Stats::buildFromClassAndItems($newEnemyClass, []);

        $battle->setupNextBattle($newEnemyStats);

        $this->battleRepository->save($battle);
    }
}
