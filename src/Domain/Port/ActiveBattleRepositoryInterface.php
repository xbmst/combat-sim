<?php

declare(strict_types=1);

namespace App\Domain\Port;

use App\Domain\Model\Battle;

interface ActiveBattleRepositoryInterface
{
    public function save(Battle $battle): void;

    public function findById(string $battleId): Battle;
}
