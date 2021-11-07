<?php
declare(strict_types=1);

namespace Aggrego\TerminalSymfonyExample\Repository;

use Aggrego\Component\BoardComponent\Application\Board\BoardRepository;
use Aggrego\Component\BoardComponent\Application\Board\Exception\BoardExist;
use Aggrego\Component\BoardComponent\Application\Board\Exception\BoardNotFound;
use Aggrego\Component\BoardComponent\Domain\Board\Board;
use Aggrego\Component\BoardComponent\Domain\Board\Id\Id;
use Aggrego\Component\BoardComponent\Domain\Board\Metadata;
use Aggrego\Component\BoardComponent\Domain\Board\Name;
use Aggrego\Component\BoardComponent\Domain\Profile\Name as ProfileName;
use Aggrego\Component\DataBoardComponent\CoreDomainPlugin\DataBoard\DataBoard;
use Psr\SimpleCache\CacheInterface;
use TimiTao\ValueObject\Standard\Required\AbstractClass\ValueObject\StringValueObject;

class CacheBoardRepository implements BoardRepository
{
    public function __construct(
        private CacheInterface $cache
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getBoardByUuid(Id $id): Board
    {
        if (!$this->cache->has($id->getValue())) {
            throw new BoardNotFound(sprintf('Not found board with "%s" id', $id->getValue()));
        }

        $data =  $this->cache->get($id->getValue());

        return new DataBoard(
            new class($data['id']) extends StringValueObject implements Id{},
            new Name($data['name']),
            ProfileName::createFromName($data['profile-name']),
            new Metadata($data['metadata'])
        );
    }

    /**
     * @inheritDoc
     */
    public function addBoard(Board $board): void
    {
        if ($this->cache->has($board->getId()->getValue())) {
            throw new BoardExist(sprintf('Exist board with "%s" id', $board->getId()->getValue()));
        }

        $data = [
            'id' => $board->getId()->getValue(),
            'name' => $board->getName()->getValue(),
            'metadata' => $board->getMetadata()->getValue(),
            'profile-name' => (string)$board->getProfileName(),
        ];

        $this->cache->set($board->getId()->getValue(), $data);
    }
}