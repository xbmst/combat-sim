<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class RoundControllerTest extends WebTestCase
{

    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function test_it_starts_next_round(): void
    {
        $battleId = Uuid::v7()->toRfc4122();
        $playerId = Uuid::v7()->toRfc4122();
        $heroClassId = 'MedievalNinja';
        $equippedItemsIds = [];

        $opponentsCount = 5;
        $this->client->request(
            'POST',
            '/api/games/start',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'battleId' => $battleId,
                'playerId' => $playerId,
                'heroClassId' => $heroClassId,
                'equippedItemsIds' => $equippedItemsIds,
                'targetBattles' => $opponentsCount,
            ], JSON_THROW_ON_ERROR)
        );

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        for ($i = 0; $i < $opponentsCount; ++$i) {
            $this->client->request(
                'POST',
                "/api/battles/$battleId/next-round",
            );

            self::assertResponseStatusCodeSame(Response::HTTP_OK);
        }
    }
}
