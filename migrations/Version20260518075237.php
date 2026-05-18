<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260518075237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Populate Character Classes';
    }

    public function up(Schema $schema): void
    {
        $sql = 'INSERT INTO class_schemas (name, base_hp, base_attack, base_defense, base_agility) VALUES (?, ?, ?, ?, ?)';

        $classes = [
            ['Barbarian', 200, 5, 1, 10],
            ['Wizard', 110, 2, 4, 20],
            ['Druid', 150, 4, 3, 15],
            ['Paladin', 170, 3, 2, 15],
            ['Rogue', 135, 3, 2, 30],
        ];

        foreach ($classes as $class) {
            $this->connection->executeStatement($sql, $class);
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('TRUNCATE class_schemas');
    }
}
