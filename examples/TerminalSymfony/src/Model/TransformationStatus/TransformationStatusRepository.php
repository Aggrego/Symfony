<?php
declare(strict_types=1);

namespace Aggrego\TerminalSymfonyExample\Model\TransformationStatus;

use Aggrego\Infrastructure\Contract\Message\CorrelatedCommand;
use Psr\SimpleCache\CacheInterface;

class TransformationStatusRepository
{
    public function __construct(
        private CacheInterface $cache
    )
    {
    }

    public function set(TransformationStatus $boardStatus): void
    {
        $this->cache->set($boardStatus->getId(), $boardStatus);
    }

    public function get(CorrelatedCommand $command): TransformationStatus
    {
        return $this->cache->get($command->getCommandId()->getValue());
    }
}