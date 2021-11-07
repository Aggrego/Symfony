<?php

/**
 * This file is part of the Aggrego.
 * (c) Tomasz Kunicki <kunicki.tomasz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Aggrego\Component\Symfony\RestUserComponent\UserInterface\Controller;

use Aggrego\Component\Symfony\RestUserComponent\UserInterface\Request\CreateBoard\CreateBoardRequest;
use Aggrego\Infrastructure\Contract\Command\Id;
use Aggrego\Infrastructure\Contract\Command\Sender;
use Aggrego\Infrastructure\Symfony\CommandClient\Client as CommandBus;
use Aggrego\Infrastructure\Symfony\MessageClient\Client as MessageClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use TimiTao\ValueObject\Standard\Required\AbstractClass\ValueObject\StringValueObject;

class CreateBoardAction
{
    #[Route('/create-board', name: self::class, methods: "POST")]
    public function __invoke(
        CreateBoardRequest $request,
        CommandBus $commandBus,
        MessageClient $messageBus
    ): JsonResponse
    {
        $id = new class(time()) extends StringValueObject implements Id {
        };
        $sender = new class('test') extends StringValueObject implements Sender {
        };
        $commandBus->consume($request->createCommand($id, $sender));
        return new JsonResponse();
    }
}
