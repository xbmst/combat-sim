<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'item_schemas')]
class ItemSchema
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
        public int $modifierHp {
            get => $this->modifierHp;
            set => $value;
        },
        #[ORM\Column(type: Types::INTEGER)]
        public int $modifierAttack {
            get => $this->modifierAttack;
            set => $value;
        },
        #[ORM\Column(type: Types::INTEGER)]
        public int $modifierDefense {
            get => $this->modifierDefense;
            set => $value;
        },
        #[ORM\Column(type: Types::INTEGER)]
        public int $modifierAgility {
            get => $this->modifierAgility;
            set => $value;
        },
        #[ORM\Column(type: Types::STRING)]
        public string $category {
            get => $this->category;
            set => $value;
        },
    ) {
    }
}
