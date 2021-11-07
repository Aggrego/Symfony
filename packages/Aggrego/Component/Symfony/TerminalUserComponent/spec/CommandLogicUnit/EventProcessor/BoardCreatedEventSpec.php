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

declare(strict_types = 1);

namespace spec\Aggrego\BasicBlockExample\CommandLogicUnit\EventProcessor;

use Aggrego\BasicBlockExample\CommandLogicUnit\EventProcessor\BoardCreatedEvent;
use Aggrego\CommandLogicUnit\EventProcessor\CommandCollection as CommandLogicUnitCommandCollection;
use Aggrego\CommandLogicUnit\EventProcessor\EventProcessor;
use Aggrego\EventConsumer\Event\Name;
use Aggrego\EventConsumer\Shared\Event;
use PhpSpec\ObjectBehavior;

class BoardCreatedEventSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(BoardCreatedEvent::class);
        $this->shouldBeAnInstanceOf(EventProcessor::class);
    }

    function it_shoult_ignore_others_than_DataBoardCreatedEvent(Event $event): void
    {
        $event->getName()->willReturn(new Name('test'));
        $this->transform($event)->shouldReturnAnInstanceOf(CommandLogicUnitCommandCollection::class);
    }
}
