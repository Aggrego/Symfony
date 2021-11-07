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

namespace spec\Aggrego\Infrastructure\Symfony\CommandClient;

use Aggrego\Infrastructure\Contract\Command\Command;
use PhpSpec\ObjectBehavior;
use stdClass;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class ClientSpec extends ObjectBehavior
{
    function let(MessageBusInterface $bus)
    {
        $this->beConstructedWith($bus);
    }

    function it_should_consume(MessageBusInterface $bus, Command $command)
    {

        $bus->dispatch($command)->willReturn(new Envelope(new stdClass()));
        $this->beConstructedWith($bus);
        $this->consume($command)->shouldReturn(null);
    }
}
