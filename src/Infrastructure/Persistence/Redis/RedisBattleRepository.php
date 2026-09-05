<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Redis;

use App\Domain\Exception\BattleNotFoundException;
use App\Domain\Model\Battle;
use App\Domain\Port\ActiveBattleRepositoryInterface;
use Redis;
use Symfony\Component\Serializer\SerializerInterface;

class RedisBattleRepository implements ActiveBattleRepositoryInterface
{
    private const string PREFIX_BATTLE = 'battle:';

    private const string PREFIX_GAME = 'game:';

    private const int TTL_SECONDS = 7200; // 2 hours

    public function __construct(
        private readonly Redis $redis,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function save(Battle $battle): void
    {
        $this->redis->setex(
            self::PREFIX_BATTLE.$battle->getBattleId(),
            self::TTL_SECONDS,
            $this->serializer->serialize($battle, 'json'),
        );

        $this->redis->setex(
            self::PREFIX_GAME.$battle->getGameId(),
            self::TTL_SECONDS,
            $battle->getBattleId(),
        );
    }

    public function findById(string $battleId): Battle
    {
        $json = $this->redis->get(self::PREFIX_BATTLE.$battleId);
        if (!is_string($json)) {
            throw new BattleNotFoundException(sprintf('Active battle "%s" not found or expired.', $battleId));
        }

        return $this->serializer->deserialize($json, Battle::class, 'json');
    }

    public function delete(Battle $battle): void
    {
        $this->redis->del(self::PREFIX_BATTLE.$battle->getBattleId());
        $this->redis->del(self::PREFIX_GAME.$battle->getGameId());
    }

    public function findByGameId(string $gameId): Battle
    {
        $battleId = $this->redis->get(self::PREFIX_GAME.$gameId);

        if (!is_string($battleId)) {
            throw new BattleNotFoundException(sprintf('Active battle by game "%s" not found or expired.', $gameId));
        }

        return $this->findById($battleId);
    }
}
