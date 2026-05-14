<?php

declare(strict_types=1);

namespace App\Infrastructure\Api;

use App\Application\Command\PlayRoundCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class RoundController extends AbstractController
{


    public function __construct(private readonly MessageBusInterface $commandBus)
    {
    }

    #[Route('/api/battles/{id}/next-round', name: 'api_battles_next_round', methods: [Request::METHOD_POST])]
    public function nextRound(string $id): JsonResponse
    {
        try {
            $this->commandBus->dispatch(new PlayRoundCommand($id));
        } catch (HandlerFailedException $e) {
            throw $e;
        } catch (ExceptionInterface $e) {
            throw $e;
        }

        return $this->json(['status' => 'round processed']);
    }
}
