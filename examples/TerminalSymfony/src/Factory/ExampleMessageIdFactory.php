<?php
declare(strict_types=1);

namespace Aggrego\TerminalSymfonyExample\Factory;

use Aggrego\Infrastructure\Contract\Message\Factory\IdFactory;
use Aggrego\Infrastructure\Contract\Message\Id;
use Ramsey\Uuid\Uuid;
use TimiTao\ValueObject\Standard\Required\AbstractClass\ValueObject\StringValueObject;

class ExampleMessageIdFactory implements IdFactory
{
    public function factory(): Id
    {
        return new class(Uuid::uuid4()->toString()) extends StringValueObject implements Id {
        };
    }
}