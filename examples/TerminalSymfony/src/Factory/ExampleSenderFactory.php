<?php
declare(strict_types=1);

namespace Aggrego\TerminalSymfonyExample\Factory;

use Aggrego\Infrastructure\Contract\Message\Factory\SenderFactory;
use Aggrego\Infrastructure\Contract\Message\Sender;
use TimiTao\ValueObject\Standard\Required\AbstractClass\ValueObject\StringValueObject;

class ExampleSenderFactory implements SenderFactory
{
    public function factory(): Sender
    {
        return new class('terminal') extends StringValueObject implements Sender {
        };
    }
}