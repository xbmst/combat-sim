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
            set(string $v) {
                $this->id = $v;
            }
            get => $this->id;
        },
        #[ORM\Column(type: Types::STRING)]
        private string $name {
            get => $this->name;
            set(string $v) {
                $this->name = $v;
            }
        },
        #[ORM\Column(type: Types::INTEGER)]
        public int $baseHp {
            set(int $v) {
                $this->baseHp = $v;
            }
            get => $this->baseHp;
        },
        #[ORM\Column(type: Types::INTEGER)]
        public int $baseAttack {
            get => $this->baseAttack;
            set(int $v) {
                $this->baseAttack = $v;
            }
        },
        #[ORM\Column(type: Types::INTEGER)]
        public int $baseDefense {
            get => $this->baseDefense;
            set(int $v) {
                $this->baseDefense = $v;
            }
        },
        #[ORM\Column(type: Types::INTEGER)]
        public int $baseAgility {
            get => $this->baseAgility;
            set(int $v) {
                $this->baseAgility = $v;
            }
        }
    ) {
    }
}
