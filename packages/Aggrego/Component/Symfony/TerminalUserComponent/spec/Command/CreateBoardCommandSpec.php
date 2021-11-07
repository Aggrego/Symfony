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

namespace spec\Aggrego\BasicBlockExample\Command;

use Aggrego\BasicBlockExample\Command\CreateBoardCommand;
use Aggrego\CommandConsumer\Client;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Command\Command;

class CreateBoardCommandSpec extends ObjectBehavior
{
    function let(Client $bus)
    {
        $this->beConstructedWith($bus);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CreateBoardCommand::class);
        $this->shouldHaveType(Command::class);
    }
}
