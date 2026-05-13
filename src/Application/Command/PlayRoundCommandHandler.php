<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Port\ActiveBattleRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class PlayRoundCommandHandler
{

    public function __construct(
        private ActiveBattleRepositoryInterface $battleRepository,
    ) {
    }

    public function __invoke(PlayRoundCommand $command): void
    {
        $battle = $this->battleRepository->findById($command->battleId);

        $battle->execute();

        $this->battleRepository->save($battle);
    }
}
