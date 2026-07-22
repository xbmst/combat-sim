<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class GameControllerTest extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function test_it_starts_game(): void
    {
        $this->client->request('GET', '/api/games/setup-data');
        $decoded = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->client->request(
            'POST',
            '/api/games/start',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'characterClassId' => $decoded['classes'][0]['id'],
                'equippedItemsIds' => [],
                'targetBattles' => 1,
            ], JSON_THROW_ON_ERROR)
        );

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $response = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('opponentName', $response);
        self::assertNotSame('', $response['opponentName']);
    }

    public function test_it_starts_game_with_max_items(): void
    {
        $this->client->request('GET', '/api/games/setup-data');
        $decoded = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $items = [];
        $categories = [];
        foreach ($decoded['items'] as $item) {
            if (!in_array($item['category'], $categories, true)) {
                $categories[] = $item['category'];
                $items[] = $item['id'];
            }
            if (count($items) === 3) {
                break;
            }
        }

        $characterClassId = $decoded['classes'][0]['id'];

        $this->client->request(
            'POST',
            '/api/games/start',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'characterClassId' => $characterClassId,
                'equippedItemsIds' => $items,
                'targetBattles' => 3,
            ], JSON_THROW_ON_ERROR)
        );

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }


    public function test_it_fetches_setup_data(): void
    {
        $this->client->request(
            'GET',
            '/api/games/setup-data',
        );

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $decoded = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertArrayHasKey('classes', $decoded);
        self::assertArrayHasKey('items', $decoded);
        self::assertArrayHasKey('rules', $decoded);
        self::assertNotEmpty($decoded['items']);
        self::assertArrayHasKey('category', $decoded['items'][0]);
    }

    public function test_it_renders_the_game_page(): void
    {
        $this->client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Choose your fighter');
        self::assertSelectorExists('[data-game-app]');
    }
}
