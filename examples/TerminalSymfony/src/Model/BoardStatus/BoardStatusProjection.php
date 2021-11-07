<?php
declare(strict_types=1);

namespace Aggrego\TerminalSymfonyExample\Model\BoardStatus;

use Aggrego\Component\BoardComponent\Contract\Application\UseCases\CreateBoard\CreateBoardCommand;
use Aggrego\Component\BoardComponent\Contract\Application\UseCases\CreateBoard\Messages\BoardCreated;
use Aggrego\Component\BoardComponent\Contract\Application\UseCases\CreateBoard\Messages\BoardNotCreated;

class BoardStatusProjection
{
    public function __construct(
        private BoardStatusRepository $boardStatusRepository
    )
    {
    }

    public function handleCreateBoardCommand(CreateBoardCommand $command): void
    {
        $this->boardStatusRepository->set(
            BoardStatus::fromCreateBoardCommand($command)
        );
    }

    public function handleBoardCreated(BoardCreated $boardCreated): void
    {
        $status =  $this->boardStatusRepository->get($boardCreated->getCorrelatedCommand());
        $status->applyBoardCreated($boardCreated);
        $this->boardStatusRepository->set($status);
    }

    public function handleBoardNotCreated(BoardNotCreated $boardNotCreated): void
    {
        $status =  $this->boardStatusRepository->get($boardNotCreated->getCorrelatedCommand());
        $status->applyBoardNotCreated($boardNotCreated);
        $this->boardStatusRepository->set($status);
    }
}