<?php
declare(strict_types=1);

namespace Aggrego\Component\Symfony\RestUserComponent\UserInterface\Request\CreateBoard;

use Aggrego\Component\BoardComponent\Contract\Application\UseCases\CreateBoard\CreateBoardCommand;
use Aggrego\Component\BoardComponent\Domain\Profile\KeyChange;
use Aggrego\Component\BoardComponent\Domain\Profile\Name as ProfileName;
use Aggrego\Infrastructure\Contract\Command\Id;
use Aggrego\Infrastructure\Contract\Command\Payload;
use Aggrego\Infrastructure\Contract\Command\Sender;
use TimiTao\ValueObject\Standard\Required\AbstractClass\ValueObject\ArrayValueObject;

class CreateBoardRequest
{
    private ?array $key = [];
    private ?string $profileName;

    public function createCommand(Id $id, Sender $sender): CreateBoardCommand
    {
        $key = new KeyChange($this->key);
        $profileName = ProfileName::createFromName($this->profileName);
        return new class($id, $sender, $key, $profileName) implements CreateBoardCommand {
            public function __construct(
                private Id $id,
                private Sender $sender,
                private KeyChange $keyChange,
                private ProfileName $name
            )
            {
            }

            public function getKey(): KeyChange
            {
                return $this->keyChange;
            }

            public function getProfile(): ProfileName
            {
                return $this->name;
            }

            public function getId(): Id
            {
                return $this->id;
            }

            public function getSender(): Sender
            {
                return $this->sender;
            }

            public function getPayload(): Payload
            {
                $data = [
                    'id' => $this->getId()->getValue(),
                    'sender' => $this->getSender()->getValue(),
                    'key' => $this->getKey()->getValue(),
                    'profile' => (string)$this->getProfile(),
                ];
                return new class($data) extends ArrayValueObject implements Payload {

                };
            }
        };
    }

}