<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'game_logs')]
class GameLog
{
    #[ORM\Id]
    #[ORM\Column(type: Types::GUID)]
    public string $id { get => $this->id; }

    public function __construct(
        #[ORM\Column(type: Types::STRING)]
        public string $gameId { get => $this->gameId; set => $value; },
        #[ORM\Column(type: Types::STRING)]
        public string $battleId { get => $this->battleId; set => $value; },
        #[ORM\Column(type: Types::STRING)]
        public string $status { get => $this->status; set => $value; },
        #[ORM\Column(type: Types::JSON, options: ['jsonb' => true])]
        public array $roundLogs { get => $this->roundLogs; set => $this->roundLogs[] = $value; },
    )
    {
        $this->id = Uuid::v7()->toRfc4122();
    }
}
