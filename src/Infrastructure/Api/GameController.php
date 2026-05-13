<?php

declare(strict_types=1);

namespace App\Infrastructure\Api;

use App\Application\Command\StartGameCommand;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class GameController extends AbstractController
{
    public function __construct(private readonly MessageBusInterface $commandBus)
    {
    }

    #[Route(path: '/api/games/start', name: 'api_games_start', methods: [Request::METHOD_POST])]
    #[OA\Post(
        summary: 'Start a new game',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Game started successfully',
                content: new OA\JsonContent(ref: new Model(type: StartGameCommand::class))
            )
        ]
    )]
    #[OA\Tag(name: 'Games')]
    public function startGame(#[MapRequestPayload] StartGameCommand $command): JsonResponse
    {
        try {
            $this->commandBus->dispatch($command);
        } catch (HandlerFailedException $e) {
            // TODO: handle real error
        } catch (ExceptionInterface $e) {
            // TODO: handle Messenger errors (eg no handler found)
        }

        return $this->json(['status' => 'game started!']);
    }
}
