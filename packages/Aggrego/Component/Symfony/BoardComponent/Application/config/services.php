<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;


use Aggrego\Component\BoardComponent\Application\UseCases\CreateBoard\CreateBoardUseCase;
use Aggrego\Component\BoardComponent\Application\UseCases\TransformBoard\TransformBoardUseCase;
use Aggrego\Component\BoardComponent\Contract\Application\UseCases\CreateBoard\CreateBoardCommand;
use Aggrego\Component\BoardComponent\Contract\Application\UseCases\TransformBoard\TransformBoardCommand;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set(CreateBoardUseCase::class, CreateBoardUseCase::class)
            ->public()
            ->autowire()
            ->autoconfigure()
            ->tag('messenger.message_handler', ['method' => 'handle', 'handles' => CreateBoardCommand::class]);

    $container->services()
        ->set(TransformBoardUseCase::class, TransformBoardUseCase::class)
            ->public()
            ->autowire()
            ->autoconfigure()
            ->tag('messenger.message_handler', ['method' => 'handle', 'handles' => TransformBoardCommand::class]);
};
