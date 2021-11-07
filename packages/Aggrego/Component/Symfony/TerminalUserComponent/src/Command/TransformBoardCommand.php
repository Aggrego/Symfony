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

use Aggrego\Component\BoardComponent\Contract\Application\UseCases\TransformBoard\TransformBoardCommand as DomainTransformBoardCommand;
use Aggrego\Component\BoardComponent\Domain\Board\Id\Id as BoardId;
use Aggrego\Component\BoardComponent\Domain\Profile\KeyChange;
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

class TransformBoardCommand extends Command
{
    private const BOARD_UUID = 'board_uuid';
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
            ->setName('core:transform-board')
            ->setDescription('Transform existing board with given data.')
            ->addArgument(self::BOARD_UUID, InputArgument::REQUIRED, 'Board id')
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
        $boardId = new class($input->getArgument(self::BOARD_UUID)) extends StringValueObject implements BoardId {
        };
        $keyChange = new KeyChange($data);

        $this->commandBus->consume(
            new class($id, $boardId, $keyChange) implements DomainTransformBoardCommand {

                public function __construct(
                    private string $id,
                    private BoardId $boardId,
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
                        'board_id' => $this->getBoardId()->getValue(),
                        'key_change' => $this->getKey()->getValue(),
                    ];

                    return new class($data) extends ArrayValueObject implements Payload {
                    };
                }

                public function getKey(): KeyChange
                {
                    return $this->keyChange;
                }

                public function getBoardId(): BoardId
                {
                    return $this->boardId;
                }
            }
        );
        return 0;
    }
}
