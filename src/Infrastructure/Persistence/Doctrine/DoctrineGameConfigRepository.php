<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Model\ValueObject\GameClass;
use App\Domain\Model\ValueObject\Item;
use App\Domain\Port\GameConfigRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Entity\ClassSchema;
use App\Infrastructure\Persistence\Doctrine\Entity\ItemSchema;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;

class DoctrineGameConfigRepository implements GameConfigRepositoryInterface
{
    private EntityRepository $gameClassRepository;
    private EntityRepository $itemsRepository;

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function getClassById(string $id): GameClass
    {
        $classSchema = $this->em->getRepository(ClassSchema::class)->find($id);

        if (!$classSchema) {
            throw new DomainException(sprintf('Hero class with ID "%s" not found', $id));
        }

        return new GameClass(
            $classSchema->getId(),
            $classSchema->getBaseHp(),
            $classSchema->getBaseAttack(),
            $classSchema->getBaseDefense(),
            $classSchema->getBaseAgility(),
        );
    }

    public function getClassByName(string $name): GameClass
    {
        /** @var ClassSchema $classSchema */
        $classSchema = $this->em->getRepository(ClassSchema::class)->findOneBy(['name' => $name]);

        if (!$classSchema) {
            throw new DomainException(sprintf('Hero class with name "%s" not found', $name));
        }

        return new GameClass(
            $classSchema->id,
            $classSchema->baseHp,
            $classSchema->baseAttack,
            $classSchema->baseDefense,
            $classSchema->baseAgility,
        );
    }

    public function getItemsByIds(array $ids): array
    {
        $qb = $this->em->createQueryBuilder();

        $schemas = $qb->select('i')
            ->from(ItemSchema::class, 'i')
            ->where($qb->expr()->in('i.id', ':ids'))
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();

        return array_map(static function (ItemSchema $schema) {
            return new Item(
                $schema->modifierHp,
                $schema->modifierAttack,
                $schema->modifierDefense,
                $schema->modifierAgility
            );
        }, $schemas);
    }

    public function getRandomEnemyClass(): GameClass
    {
        $classSchemas = $this->em->getRepository(ClassSchema::class)->findAll();
        $classSchema = $classSchemas[0];

        if (!$classSchema) {
            throw new DomainException('Enemy class not found');
        }

        return new GameClass(
            $classSchema->id,
            $classSchema->baseHp,
            $classSchema->baseAttack,
            $classSchema->baseDefense,
            $classSchema->baseAgility,
        );
    }
}
