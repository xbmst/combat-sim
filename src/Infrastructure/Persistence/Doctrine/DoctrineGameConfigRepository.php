<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Exception\CharacterClassNotFoundException;
use App\Domain\Exception\ItemClassNotFoundException;
use App\Domain\Port\GameConfigRepositoryInterface;
use App\Domain\ValueObject\CharacterLoadout;
use App\Domain\ValueObject\GameClass;
use App\Domain\ValueObject\Item;
use App\Infrastructure\Persistence\Doctrine\Entity\ClassSchema;
use App\Infrastructure\Persistence\Doctrine\Entity\ItemSchema;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;

readonly class DoctrineGameConfigRepository implements GameConfigRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em, private Connection $connection)
    {
    }

    public function getClassById(string $id): GameClass
    {
        $classSchema = $this->em->getRepository(ClassSchema::class)->find($id);

        if (!$classSchema) {
            throw new CharacterClassNotFoundException(sprintf('Character class with ID "%s" not found', $id));
        }

        return new GameClass(
            $classSchema->id,
            $classSchema->name,
            $classSchema->baseHp,
            $classSchema->baseAttack,
            $classSchema->baseDefense,
            $classSchema->baseAgility,
        );
    }

    public function getClassByName(string $name): GameClass
    {
        /** @var ClassSchema $classSchema */
        $classSchema = $this->em->getRepository(ClassSchema::class)->findOneBy(['name' => $name]);

        if (!$classSchema) {
            throw new CharacterClassNotFoundException(sprintf('Character class with name "%s" not found', $name));
        }

        return new GameClass(
            $classSchema->id,
            $classSchema->name,
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
                $schema->id,
                $schema->name,
                $schema->category,
                $schema->modifierAttack,
                $schema->modifierDefense,
                $schema->modifierAgility,
            );
        }, $schemas);
    }

    public function getRandomOpponentClass(): GameClass
    {
        $classSchemas = $this->em->getRepository(ClassSchema::class)->findAll();

        if (!$classSchemas) {
            throw new CharacterClassNotFoundException('Classes data is not populated');
        }

        $classSchema = $classSchemas[random_int(0, count($classSchemas) - 1)];

        if (!$classSchema) {
            throw new CharacterClassNotFoundException('Opponent class not found');
        }

        return new GameClass(
            $classSchema->id,
            $classSchema->name,
            $classSchema->baseHp,
            $classSchema->baseAttack,
            $classSchema->baseDefense,
            $classSchema->baseAgility,
        );
    }

    public function getRandomItems(int $limit = CharacterLoadout::MAX_ITEMS): array
    {
        $qb = $this->em->createQueryBuilder();

        $schemas = $qb->select('i')
            ->from(ItemSchema::class, 'i')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        if (!$schemas) {
            throw new ItemClassNotFoundException();
        }

        return array_map(static function (ItemSchema $schema) {
            return new Item(
                $schema->id,
                $schema->name,
                $schema->category,
                $schema->modifierAttack,
                $schema->modifierDefense,
                $schema->modifierAgility
            );
        }, $schemas);
    }

    public function getItemNamesFromItems(array $items): array
    {
        return array_map(static function (Item $item) {
            return $item->name;
        }, $items);
    }

    public function getAllClasses(): array
    {
        return $this->connection->fetchAllAssociative(
            'SELECT id, name, base_hp as baseHp, base_attack as baseAttack, base_defense as baseDefense, base_agility as baseAgility FROM class_schemas'
        );
    }

    public function getAllItems(): array
    {
        return $this->connection->fetchAllAssociative(
            'SELECT id, name, category, modifier_attack as modifierAttack, modifier_defense as modifierDefense, modifier_agility as modifierAgility FROM item_schemas'
        );
    }
}
