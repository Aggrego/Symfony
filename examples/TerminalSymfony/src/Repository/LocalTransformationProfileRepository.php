<?php
declare(strict_types=1);

namespace Aggrego\TerminalSymfonyExample\Repository;

use Aggrego\Component\BasicBlockComponent\CoreDomainPlugin\Profile\BasicBlockProfile;
use Aggrego\Component\BoardComponent\Application\Profile\Transformation\Exception\TransformationProfileNotFound;
use Aggrego\Component\BoardComponent\Application\Profile\Transformation\TransformationProfileRepository;
use Aggrego\Component\BoardComponent\Domain\Profile\Name;
use Aggrego\Component\BoardComponent\Domain\Profile\Transformation\TransformationProfile;

class LocalTransformationProfileRepository implements TransformationProfileRepository
{
    public function __construct(
        private BasicBlockProfile $basicBlockProfile
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getByName(Name $name): TransformationProfile
    {
        if (!$this->basicBlockProfile->getName()->equal($name)) {
            throw new TransformationProfileNotFound();
        }
        return $this->basicBlockProfile;
    }
}