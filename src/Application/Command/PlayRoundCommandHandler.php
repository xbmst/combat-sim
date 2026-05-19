<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Dto\PlayRoundCommandResponse;
use App\Application\Event\BattleWonEvent;
use App\Application\Event\GameOverEvent;
use App\Application\Event\GameWonEvent;
use App\Application\Event\NextTurnEvent;
use App\Domain\Model\Battle;
use App\Domain\Port\ActiveBattleRepositoryInterface;
use App\Domain\Port\EventBusInterface;
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
        private EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(PlayRoundCommand $command): PlayRoundCommandResponse
    {
        $battle = $this->battleRepository->findByGameId($command->gameId);

        $battle->execute($this->damageCalculator, $this->diceRoller, $this->turnPicker);

        if ($battle->isCharacterDead()) {
            $this->battleRepository->delete($battle->getBattleId());

            $this->eventBus->dispatch(
                new GameOverEvent($command->gameId, $battle->getBattleId(), $battle->getRoundLogs())
            );

            return $this->result(BattleStatus::GAME_OVER, $battle);
        }

        if ($battle->isAllRoundsComplete()) {
            $this->battleRepository->delete($battle->getBattleId());

            $this->eventBus->dispatch(
                new GameWonEvent($command->gameId, $battle->getBattleId(), $battle->getRoundLogs())
            );

            return $this->result(BattleStatus::GAME_WON, $battle);
        }

        if ($battle->isOpponentDead()) {
            $battle->setupNextBattle($this->opponentBuilder->build());

            $this->eventBus->dispatch(
                new BattleWonEvent($command->gameId, $battle->getBattleId(), $battle->getRoundLogs())
            );

            $result = $this->result(BattleStatus::BATTLE_WON, $battle);
        } else {
            $this->eventBus->dispatch(
                new NextTurnEvent($command->gameId, $battle->getBattleId(), $battle->getRoundLogs())
            );

            $result = $this->result(BattleStatus::NEXT_TURN, $battle);
        }

        $this->battleRepository->save($battle);

        return $result;
    }

    private function result(BattleStatus $status, Battle $battle): PlayRoundCommandResponse
    {
        return new PlayRoundCommandResponse(
            $status,
            $battle->getRoundLogs(),
            $battle->getCharacter()->stats->currentHp,
            $battle->getOpponent()->stats->currentHp
        );
    }
}
