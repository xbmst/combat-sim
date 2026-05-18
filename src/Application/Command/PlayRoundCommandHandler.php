<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Port\ActiveBattleRepositoryInterface;
use App\Domain\Service\DamageCalculatorInterface;
use App\Domain\Service\DiceRollerInterface;
use App\Domain\Service\OpponentBuilder;
use App\Domain\Service\TurnPickerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class PlayRoundCommandHandler
{

    public function __construct(
        private ActiveBattleRepositoryInterface $battleRepository,
        private DamageCalculatorInterface $damageCalculator,
        private DiceRollerInterface $diceRoller,
        private TurnPickerInterface $turnPicker,
        private OpponentBuilder $opponentBuilder,
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
            $battle->setupNextBattle($this->opponentBuilder->build());
        }

        $this->battleRepository->save($battle);
    }
}
