<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Dto\PlayRoundCommandResult;
use App\Domain\Model\Battle;
use App\Domain\Port\ActiveBattleRepositoryInterface;
use App\Domain\Service\DamageCalculatorInterface;
use App\Domain\Service\DiceRollerInterface;
use App\Domain\Service\OpponentBuilder;
use App\Domain\Service\TurnPickerInterface;
use App\Domain\ValueObject\BattleStatus;
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

    public function __invoke(PlayRoundCommand $command): PlayRoundCommandResult
    {
        $battle = $this->battleRepository->findById($command->battleId);

        $battle->execute($this->damageCalculator, $this->diceRoller, $this->turnPicker);

        if ($battle->isCharacterDead()) {
            $this->battleRepository->delete($battle->getBattleId());
            // TODO: $this->eventBus->dispatch(new GameLostEvent(...)); save to sql
            return $this->result(BattleStatus::GAME_OVER, $battle);
        }

        if ($battle->isAllRoundsComplete()) {
            $this->battleRepository->delete($battle->getBattleId());
            // TODO: $this->eventBus->dispatch(new GameWonEvent(...));
            return $this->result(BattleStatus::GAME_WON, $battle);
        }

        if ($battle->isOpponentDead()) {
            $battle->setupNextBattle($this->opponentBuilder->build());

            $result = $this->result(BattleStatus::BATTLE_WON, $battle);
        } else {
            $result = $this->result(BattleStatus::NEXT_TURN, $battle);
        }

        $this->battleRepository->save($battle);

        return $result;
    }

    private function result(BattleStatus $status, Battle $battle): PlayRoundCommandResult
    {
        return new PlayRoundCommandResult(
            $status,
            $battle->getRoundLogs(),
            $battle->getCharacter()->stats->currentHp,
            $battle->getOpponent()->stats->currentHp
        );
    }
}
