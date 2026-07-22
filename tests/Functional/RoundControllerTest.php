<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Domain\ValueObject\BattleStatus;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RoundControllerTest extends WebTestCase
{

    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function test_it_starts_next_round(): void
    {
        $opponentsCount = 5;
        $this->client->request(
            'POST',
            '/api/games/start',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'characterClassId' => '123e4567-e89b-12d3-a456-426614174000',
                'equippedItemsIds' => [],
                'targetBattles' => $opponentsCount,
            ], JSON_THROW_ON_ERROR)
        );

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $decoded = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $gameId = $decoded['gameId'];

        do {
            $this->client->request(
                'POST',
                "/api/games/$gameId/next-round",
            );

            self::assertResponseStatusCodeSame(Response::HTTP_OK);

            $responseContent = $this->client->getResponse()->getContent();
            $decoded = json_decode($responseContent, true, 512, JSON_THROW_ON_ERROR);

            self::assertIsArray($decoded);
            self::assertArrayHasKey('status', $decoded);
            self::assertArrayHasKey('opponentName', $decoded);
            self::assertNotSame('', $decoded['opponentName']);

        } while (
            $decoded['status'] !== BattleStatus::BATTLE_WON->value
            && $decoded['status'] !== BattleStatus::GAME_WON->value
            && $decoded['status'] !== BattleStatus::GAME_OVER->value
        );
    }
}
