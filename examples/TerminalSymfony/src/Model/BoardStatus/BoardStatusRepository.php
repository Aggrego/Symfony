<?php
declare(strict_types=1);

namespace Aggrego\TerminalSymfonyExample\Model\BoardStatus;

use Aggrego\Infrastructure\Contract\Message\CorrelatedCommand;
use Psr\SimpleCache\CacheInterface;

class BoardStatusRepository
{
    public function __construct(
        private CacheInterface $cache
    )
    {
    }

    public function set(BoardStatus $boardStatus): void
    {
        $this->cache->set($boardStatus->getId(), $boardStatus);
    }

    public function get(CorrelatedCommand $command): BoardStatus
    {
        return $this->cache->get($command->getCommandId()->getValue());
    }
}