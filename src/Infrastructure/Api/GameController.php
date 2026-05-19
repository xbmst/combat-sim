<?php

declare(strict_types=1);

namespace App\Infrastructure\Api;

use App\Application\Command\StartGameCommand;
use App\Application\Query\GetGameLogHandler;
use App\Application\Query\GetSetupDataQuery;
use App\Application\Query\GetSetupDataQueryHandler;
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
            throw $e;
        } catch (ExceptionInterface $e) {
            throw $e;
        }

        return $this->json(['status' => 'game started!']);
    }

    // TODO: consider separate controller
    #[Route('/api/games/setup-data', methods: [Request::METHOD_GET])]
    #[OA\Get(summary: 'Get all available classes and items to build a Character')]
    public function getSetupData(GetSetupDataQueryHandler $handler): JsonResponse
    {
        $data = $handler->__invoke(new GetSetupDataQuery());

        return $this->json($data);
    }

    #[Route('/api/games/{id}/logs', methods: [Request::METHOD_GET])]
    #[OA\Get(summary: 'Get game logs')]
    public function getLogs(string $id, GetGameLogHandler $handler): JsonResponse
    {
        return $this->json($handler->__invoke($id));
    }
}
