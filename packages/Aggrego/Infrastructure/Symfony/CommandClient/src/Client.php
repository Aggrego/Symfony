<?php

/**
 *
 * This file is part of the Aggrego.
 * (c) Tomasz Kunicki <kunicki.tomasz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

declare(strict_types=1);

namespace Aggrego\Infrastructure\Symfony\CommandClient;

use Aggrego\Infrastructure\Contract\Command\Command;
use Aggrego\Infrastructure\Contract\CommandClient\Client as CommandInfrastructureClient;
use Symfony\Component\Messenger\MessageBusInterface;

class Client implements CommandInfrastructureClient
{
    public function __construct(
        private MessageBusInterface $commandBus
    ) {
    }

    public function consume(Command $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
