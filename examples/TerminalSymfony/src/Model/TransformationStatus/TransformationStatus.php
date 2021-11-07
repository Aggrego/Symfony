<?php
declare(strict_types=1);

namespace Aggrego\TerminalSymfonyExample\Model\TransformationStatus;

use Aggrego\Component\BoardComponent\Contract\Application\UseCases\TransformBoard\TransformBoardCommand;
use Aggrego\Component\BoardComponent\Contract\Application\UseCases\TransformBoard\Messages\BoardCreated;
use Aggrego\Component\BoardComponent\Contract\Application\UseCases\TransformBoard\Messages\BoardNotTransformed;

class TransformationStatus
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

    public static function fromTransformBoardCommand(TransformBoardCommand $command): self
    {
        return new self(
            $command->getId()->getValue(),
            $command->getSender()->getValue(),
            'init',
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

    public function applyBoardNotTransformed(BoardNotTransformed $boardNotTransformed): void
    {
        if ($this->id !== $boardNotTransformed->getCorrelatedCommand()->getCommandId()->getValue()) {
            return;
        }

        $this->status = 'invalid';
        $this->code = $boardNotTransformed->getPayload()->getValue()['code'];
        $this->message = $boardNotTransformed->getPayload()->getValue()['message'];
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

    public function getSender(): string
    {
        return $this->sender;
    }

    public function getBoardId(): string
    {
        return $this->boardId;
    }
}