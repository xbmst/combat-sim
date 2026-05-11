<?php

declare(strict_types=1);

namespace App\Domain\Port;

use App\Domain\Model\Entity\Battle;

interface ActiveBattleRepositoryInterface
{
    public function save(Battle $battle): void;
}
