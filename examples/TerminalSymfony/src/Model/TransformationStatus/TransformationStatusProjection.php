<?php
declare(strict_types=1);

namespace Aggrego\TerminalSymfonyExample\Model\TransformationStatus;

use Aggrego\Component\BoardComponent\Contract\Application\UseCases\TransformBoard\TransformBoardCommand;
use Aggrego\Component\BoardComponent\Contract\Application\UseCases\TransformBoard\Messages\BoardCreated;
use Aggrego\Component\BoardComponent\Contract\Application\UseCases\TransformBoard\Messages\BoardNotTransformed;

class TransformationStatusProjection
{
    public function __construct(
        private TransformationStatusRepository $transformationStatusRepository
    )
    {
    }

    public function handleTransformBoardCommand(TransformBoardCommand $command): void
    {
        $this->transformationStatusRepository->set(
            TransformationStatus::fromTransformBoardCommand($command)
        );
    }

    public function handleBoardCreated(BoardCreated $boardCreated): void
    {
        $status =  $this->transformationStatusRepository->get($boardCreated->getCorrelatedCommand());
        $status->applyBoardCreated($boardCreated);
        $this->transformationStatusRepository->set($status);
    }

    public function handleBoardNotTransformed(BoardNotTransformed $boardNotCreated): void
    {
        $status =  $this->transformationStatusRepository->get($boardNotCreated->getCorrelatedCommand());
        $status->applyBoardNotTransformed($boardNotCreated);
        $this->transformationStatusRepository->set($status);
    }
}