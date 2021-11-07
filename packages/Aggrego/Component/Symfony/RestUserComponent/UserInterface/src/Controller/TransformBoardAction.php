<?php
declare(strict_types=1);

namespace Aggrego\Component\Symfony\RestUserComponent\UserInterface\Controller;

use Aggrego\Component\Symfony\RestUserComponent\UserInterface\Request\TransformBoard\TransformBoardRequest;
use Aggrego\Infrastructure\Symfony\CommandClient\Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TransformBoardAction
{
    #[Route('/transform-board', name: self::class, methods: "POST")]
    public function __invoke(TransformBoardRequest $request, Client $commandBus): JsonResponse
    {
        return new JsonResponse();
    }
}