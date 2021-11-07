<?php
declare(strict_types=1);

namespace Aggrego\TerminalSymfonyExample\Repository;

use Aggrego\Component\BasicBlockComponent\CoreDomainPlugin\Profile\BasicBlockProfile;
use Aggrego\Component\BoardComponent\Application\Profile\Building\BuildingProfileRepository;
use Aggrego\Component\BoardComponent\Application\Profile\Building\Exception\BuildingProfileNotFound;
use Aggrego\Component\BoardComponent\Domain\Profile\Building\BuildingProfile;
use Aggrego\Component\BoardComponent\Domain\Profile\Name;

class LocalBuildingProfileRepository implements BuildingProfileRepository
{
    public function __construct(
        private BasicBlockProfile $basicBlockProfile
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getByName(Name $name): BuildingProfile
    {
        if (!$this->basicBlockProfile->getName()->equal($name)) {
            throw new BuildingProfileNotFound();
        }
        return $this->basicBlockProfile;
    }
}