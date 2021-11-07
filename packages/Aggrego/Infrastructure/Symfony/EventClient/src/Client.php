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

namespace Aggrego\Infrastructure\Symfony\EventClient;

use Aggrego\Infrastructure\Contract\Event\Event;
use Aggrego\Infrastructure\Contract\EventClient\Client as EventInfrastructureClient;
use Symfony\Component\Messenger\MessageBusInterface;

class Client implements EventInfrastructureClient
{
    public function __construct(
        private MessageBusInterface $eventBus
    ) {
    }

    public function consume(Event $event): void
    {
        $this->eventBus->dispatch($event);
    }
}
