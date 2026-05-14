<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Infrastructure\Persistence\Doctrine\Entity\ClassSchema;
use App\Infrastructure\Persistence\Doctrine\Entity\ItemSchema;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
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
        $itemSchema = new ItemSchema(
            Uuid::v7()->toRfc4122(),
            5,
            6,
            7,
            3
        );

        $manager->persist($classSchema);
        $manager->persist($itemSchema);
        $manager->flush();
    }
}
