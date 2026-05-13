<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Redis;

use App\Domain\Model\Entity\Battle;
use App\Domain\Port\ActiveBattleRepositoryInterface;

class RedisActiveBattleRepository implements ActiveBattleRepositoryInterface
{

    public function save(Battle $battle): void
    {
        // TODO: Implement save() method.
    }
}
