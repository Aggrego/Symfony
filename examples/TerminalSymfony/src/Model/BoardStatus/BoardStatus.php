<?php
declare(strict_types=1);

namespace Aggrego\TerminalSymfonyExample\Model\BoardStatus;

use Aggrego\Component\BoardComponent\Contract\Application\UseCases\CreateBoard\CreateBoardCommand;
use Aggrego\Component\BoardComponent\Contract\Application\UseCases\CreateBoard\Messages\BoardCreated;
use Aggrego\Component\BoardComponent\Contract\Application\UseCases\CreateBoard\Messages\BoardNotCreated;

class BoardStatus
{
    public function __construct(
        private string $id,
        private string $sender,
        private string $status,
        private int $code = 0,
        private string $message = '',
        private string $boardId = '',
    )
    {
    }

    public static function fromCreateBoardCommand(CreateBoardCommand $command): self
    {
        return new self(
            $command->getId()->getValue(),
            $command->getSender()->getValue(),
            'init'
        );
    }

    public function applyBoardCreated(BoardCreated $boardCreated): void
    {
        if ($this->id !== $boardCreated->getCorrelatedCommand()->getCommandId()->getValue()) {
            return;
        }
        $this->status = 'success';
        $this->code = $boardCreated->getPayload()->getValue()['code'];
        $this->message = $boardCreated->getPayload()->getValue()['message'];
        $this->boardId = $boardCreated->getPayload()->getValue()['board_id'];
    }

    public function applyBoardNotCreated(BoardNotCreated $boardNotCreated): void
    {
        if ($this->id !== $boardNotCreated->getCorrelatedCommand()->getCommandId()->getValue()) {
            return;
        }

        $this->status = 'invalid';
        $this->code = $boardNotCreated->getPayload()->getValue()['code'];
        $this->message = $boardNotCreated->getPayload()->getValue()['message'];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getBoardId(): string
    {
        return $this->boardId;
    }

    public function getSender(): string
    {
        return $this->sender;
    }
}