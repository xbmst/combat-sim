<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Infrastructure\Persistence\Doctrine\Entity\ClassSchema;
use App\Infrastructure\Persistence\Doctrine\Entity\ItemSchema;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;
use Symfony\Component\Uid\Uuid;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $classSchema = new ClassSchema(
            Uuid::v7()->toRfc4122(),
            'MedievalNinja',
            150,
            6,
            5,
            5
        );

        $manager->persist($classSchema);

        for ($i = 0; $i < 5; $i++) {
            $manager->persist($this->buildRandomClassSchema());
            $manager->persist($this->buildRandomItemSchema());
        }

        $manager->flush();
    }

    /**
     * @throws RandomException
     */
    private function buildRandomClassSchema(): ClassSchema
    {
        return new ClassSchema(
            Uuid::v7()->toRfc4122(),
            'Medieval' . random_int(1, 100),
            random_int(150, 250),
            random_int(1, 15),
            random_int(1, 15),
            random_int(1, 15),
        );
    }

    /**
     * @throws RandomException
     */
    private function buildRandomItemSchema(): ItemSchema
    {
        return new ItemSchema(
            Uuid::v7()->toRfc4122(),
            'Item'. random_int(1, 100),
            random_int(1, 10),
            random_int(1, 10),
            random_int(1, 10),
            random_int(1, 10),
        );
    }
}
