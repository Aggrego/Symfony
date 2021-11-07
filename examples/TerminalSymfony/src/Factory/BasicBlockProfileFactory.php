<?php
declare(strict_types=1);

namespace Aggrego\TerminalSymfonyExample\Factory;

use Aggrego\Component\BasicBlockComponent\CoreDomainPlugin\Profile\BasicBlockProfile;
use Aggrego\Component\BoardComponent\Domain\Board\Name as BoardName;
use Aggrego\Component\BoardComponent\Domain\BoardPrototype\Name as PrototypeName;
use Aggrego\Component\DataBoardComponent\CoreDomainPlugin\BoardPrototype\DataPrototype;

class BasicBlockProfileFactory
{
    public function __invoke(): BasicBlockProfile
    {
        return new BasicBlockProfile(
            new PrototypeName(DataPrototype::NAME),
            new BoardName(DataPrototype::NAME)
        );
    }
}