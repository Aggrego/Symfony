<?php

return [
    //region Components
    Aggrego\Component\Symfony\TerminalUserComponent\TerminalUserComponentBundle::class => ['all' => true],
    //endregion

    //region Infrastructure
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
    Aggrego\Infrastructure\Symfony\CommandClient\CommandClientBundle::class => ['all' => true],
    Aggrego\Infrastructure\Symfony\MessageClient\MessageClientBundle::class => ['all' => true],
    Aggrego\Infrastructure\Symfony\EventClient\EventClientBundle::class => ['all' => true],
    Aggrego\Component\Symfony\BoardComponent\Application\BoardComponentApplicationBundle::class => ['all' => true],
    //endregion
];
