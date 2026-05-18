<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Model\Stats;
use App\Domain\Model\Warrior;
use App\Domain\Port\ActiveBattleRepositoryInterface;
use App\Domain\Port\GameConfigRepositoryInterface;
use App\Domain\Service\DamageCalculatorInterface;
use App\Domain\Service\DiceRollerInterface;
use App\Domain\Service\TurnPickerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class PlayRoundCommandHandler
{

    public function __construct(
        private ActiveBattleRepositoryInterface $battleRepository,
        private GameConfigRepositoryInterface $configRepository,
        private DamageCalculatorInterface $damageCalculator,
        private DiceRollerInterface $diceRoller,
        private TurnPickerInterface $turnPicker,
    ) {
    }

    public function __invoke(PlayRoundCommand $command): void
    {
        $battle = $this->battleRepository->findById($command->battleId);

        $battle->execute($this->damageCalculator, $this->diceRoller, $this->turnPicker);

        if ($battle->isCharacterDead()) {
            $this->battleRepository->delete($battle->getBattleId());
            // TODO: $this->eventBus->dispatch(new GameLostEvent(...)); save to sql
            return;
        }

        if ($battle->isAllRoundsComplete()) {
            $this->battleRepository->delete($battle->getBattleId());
            // TODO: $this->eventBus->dispatch(new GameWonEvent(...));
            return;
        }

        if ($battle->isOpponentDead()) {
            $newOpponentClass = $this->configRepository->getRandomOpponentClass();
            $newOpponent = new Warrior($newOpponentClass->name, Stats::buildFromClassAndItems($newOpponentClass, []));

            $battle->setupNextBattle($newOpponent);
        }

        $this->battleRepository->save($battle);
    }
}
