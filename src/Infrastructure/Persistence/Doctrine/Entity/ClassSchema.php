<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'class_schemas')]
class ClassSchema
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: Types::GUID)]
        public string $id {
            get => $this->id;
            set => $value;
        },
        #[ORM\Column(type: Types::STRING)]
        public string $name {
            get => $this->name;
            set => $value;
        },
        #[ORM\Column(type: Types::INTEGER)]
        public int $baseHp {
            get => $this->baseHp;
            set => $value;
        },
        #[ORM\Column(type: Types::INTEGER)]
        public int $baseAttack {
            get => $this->baseAttack;
            set => $value;
        },
        #[ORM\Column(type: Types::INTEGER)]
        public int $baseDefense {
            get => $this->baseDefense;
            set => $value;
        },
        #[ORM\Column(type: Types::INTEGER)]
        public int $baseAgility {
            get => $this->baseAgility;
            set => $value;
        }
    ) {
    }
}
