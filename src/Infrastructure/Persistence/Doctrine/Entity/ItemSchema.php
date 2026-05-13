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
        },
        #[ORM\Column(type: Types::INTEGER)]
        public int $modifierHp {
            get => $this->modifierHp;
            set(int $v) {
                $this->modifierHp = $v;
            }
        },
        #[ORM\Column(type: Types::INTEGER)]
        public int $modifierAttack {
            get => $this->modifierAttack;
            set(int $v) {
                $this->modifierAttack = $v;
            }
        },
        #[ORM\Column(type: Types::INTEGER)]
        public int $modifierDefense {
            get => $this->modifierDefense;
            set(int $v) {
                $this->modifierDefense = $v;
            }
        },
        #[ORM\Column(type: Types::INTEGER)]
        public int $modifierAgility {
            get => $this->modifierAgility;
            set(int $v) {
                $this->modifierAgility = $v;
            }
        }
    ) {
    }
}
