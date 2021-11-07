<?php
/**
 *
 * This file is part of the Aggrego.
 * (c) Tomasz Kunicki <kunicki.tomasz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

declare(strict_types=1);

namespace Aggrego\Component\Symfony\TerminalUserComponent\Command;

use Aggrego\Component\BoardComponent\Contract\Application\UseCases\CreateBoard\CreateBoardCommand as DomainCreateBoardCommand;
use Aggrego\Component\BoardComponent\Domain\Profile\KeyChange;
use Aggrego\Component\BoardComponent\Domain\Profile\Name as ProfileName;
use Aggrego\Infrastructure\Contract\Command\Id;
use Aggrego\Infrastructure\Contract\Command\Payload;
use Aggrego\Infrastructure\Contract\Command\Sender;
use Aggrego\Infrastructure\Contract\CommandClient\Client;
use Assert\Assertion;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TimiTao\ValueObject\Standard\Required\AbstractClass\ValueObject\ArrayValueObject;
use TimiTao\ValueObject\Standard\Required\AbstractClass\ValueObject\StringValueObject;


class CreateBoardCommand extends Command
{
    private const PROFILE = 'profile';
    private const VERSION = 'version';
    private const DATA_FILE_SOURCE = 'data_file_source';

    /** @var Client */
    private $commandBus;

    public function __construct(Client $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }

    protected function configure()
    {
        $this
            ->setName('core:create-board')
            ->setDescription('Create new board based on key data.')
            ->addArgument(self::PROFILE, InputArgument::REQUIRED, 'Profile name')
            ->addArgument(self::VERSION, InputArgument::REQUIRED, 'Profile version')
            ->addOption(self::DATA_FILE_SOURCE, 'f', InputOption::VALUE_REQUIRED, 'Profile data in JSON format in file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = null;
        $file = $input->getOption(self::DATA_FILE_SOURCE);
        if ($file) {
            Assertion::file($file);
            $fileContent = file_get_contents($file);
            Assertion::isJsonString($fileContent);
            $data = json_decode($fileContent, true);
        }

        if ($data === null) {
            throw new Exception('No data was loaded correctly.');
        }

        $id = Uuid::uuid4()->toString();
        $keyChange = new KeyChange($data);
        $profile = ProfileName::createFromParts(
            $input->getArgument(self::PROFILE),
            $input->getArgument(self::VERSION)
        );

        $this->commandBus->consume(
            new class($id, $profile, $keyChange) implements DomainCreateBoardCommand {

                public function __construct(
                    private string $id,
                    private ProfileName $name,
                    private KeyChange $keyChange
                )
                {
                }

                public function getId(): Id
                {
                    return new class($this->id) extends StringValueObject implements Id {
                    };
                }

                public function getSender(): Sender
                {
                    return new class('terminal') extends StringValueObject implements Sender {
                    };
                }

                public function getPayload(): Payload
                {
                    $data = [
                        'id' => $this->id,
                        'key_change' => $this->getKey()->getValue(),
                        'profile_name' => (string)$this->getProfile(),
                    ];

                    return new class($data) extends ArrayValueObject implements Payload {
                    };
                }

                public function getKey(): KeyChange
                {
                    return $this->keyChange;
                }

                public function getProfile(): ProfileName
                {
                    return $this->name;
                }
            }
        );

        return 0;
    }
}
