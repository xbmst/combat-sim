<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Redis;

use App\Domain\Exception\BattleNotFoundException;
use App\Domain\Model\Battle;
use App\Domain\Model\Stats;
use App\Domain\Model\Warrior;
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
        $character = $battle->getCharacter();
        $opponent = $battle->getOpponent();

        $data = [
            'battleId' => $battle->getBattleId(),
            'currentRound' => $battle->getCurrentRound(),
            'roundLogs' => $battle->getRoundLogs(),
            'targetBattles' => $battle->getTargetBattles(),
            'character' => [ // TODO: toArray()
                'maxHp' => $character->stats->maxHp,
                'currentHp' => $character->stats->currentHp,
                'attack' => $character->stats->attack,
                'defense' => $character->stats->defense,
                'agility' => $character->stats->agility,
                'name' => $character->name,
                'items' => $character->items,
            ],
            'opponent' => [
                'maxHp' => $opponent->stats->maxHp,
                'currentHp' => $opponent->stats->currentHp,
                'attack' => $opponent->stats->attack,
                'defense' => $opponent->stats->defense,
                'agility' => $opponent->stats->agility,
                'name' => $opponent->name,
                'items' => $opponent->items,
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
        // TODO: fromArray()
        $character = new Warrior(
            $data['character']['name'],
            new Stats(
                $data['character']['maxHp'],
                $data['character']['currentHp'],
                $data['character']['attack'],
                $data['character']['defense'],
                $data['character']['agility'],
            ),
            $data['character']['items'],
        );

        $opponent = new Warrior($data['character']['name'],
            new Stats(
                $data['opponent']['maxHp'],
                $data['opponent']['currentHp'],
                $data['opponent']['attack'],
                $data['opponent']['defense'],
                $data['opponent']['agility'],
            ),
            $data['opponent']['items'],
        );

        return new Battle(
            $battleId,
            $character,
            $opponent,
            $data['targetBattles'],
            $data['currentRound'],
            $data['roundLogs'],
        );
    }

    public function delete(string $id): void
    {
        $this->redis->del(self::PREFIX . $id);
    }
}
