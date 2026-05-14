<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Redis;

use App\Domain\Exception\BattleNotFoundException;
use App\Domain\Model\Entity\Battle;
use App\Domain\Model\Stats;
use App\Domain\Port\ActiveBattleRepositoryInterface;
use Redis;

class RedisBattleRepository implements ActiveBattleRepositoryInterface
{
    private const string PREFIX = 'battle:';
    private const int TTL_SECONDS = 7200; // 2 hours

    public function __construct(
        private readonly Redis $redis,
    ) {
    }

    public function save(Battle $battle): void
    {
        $heroStats = $battle->getHeroStats();
        $enemyStats = $battle->getEnemyStats();

        $data = [
            'battleId' => $battle->getBattleId(),
            'currentRound' => $battle->getCurrentRound(),
            'roundLogs' => $battle->getRoundLogs(),
            'hero' => [
                'maxHp' => $heroStats->maxHp,
                'currentHp' => $heroStats->currentHp,
                'attack' => $heroStats->attack,
                'defense' => $heroStats->defense,
                'agility' => $heroStats->agility,
            ],
            'enemy' => [
                'maxHp' => $enemyStats->maxHp,
                'currentHp' => $enemyStats->currentHp,
                'attack' => $enemyStats->attack,
                'defense' => $enemyStats->defense,
                'agility' => $enemyStats->agility,
            ],
        ];

        $this->redis->setex(
            self::PREFIX . $battle->getBattleId(),
            self::TTL_SECONDS,
            json_encode($data, JSON_THROW_ON_ERROR)
        );
    }

    public function findById(string $battleId): Battle
    {
        $json = $this->redis->get(self::PREFIX . $battleId);
        if (!$json) {
            throw new BattleNotFoundException(sprintf('Active battle "%s" not found or expired.', $battleId));
        }

        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        $heroStats = new Stats(
            $data['hero']['maxHp'],
            $data['hero']['currentHp'],
            $data['hero']['attack'],
            $data['hero']['defense'],
            $data['hero']['agility'],
        );

        $enemyStats = new Stats(
            $data['enemy']['maxHp'],
            $data['enemy']['currentHp'],
            $data['enemy']['attack'],
            $data['enemy']['defense'],
            $data['enemy']['agility'],
        );

        return new Battle(
            $battleId,
            $heroStats,
            $enemyStats,
            $data['currentRound'],
            $data['roundLogs'],
        );
    }
}
