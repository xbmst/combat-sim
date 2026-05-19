<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Domain\ValueObject\ItemCategory;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260518075314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Populate Items';
    }

    public function up(Schema $schema): void
    {
        $sql = 'INSERT INTO item_schemas (name, modifier_hp, modifier_attack, modifier_defense, modifier_agility, category) VALUES (?, 0, ?, ?, ?, ?)';

        $classes = [
            ['Sword', 5, -1, -10, ItemCategory::WEAPON->value],
            ['Long Sword', 8, -2, -15, ItemCategory::WEAPON->value],
            ['Dagger', 2, 0, 5, ItemCategory::WEAPON->value],
            ['Battleaxe', 9, -4, -7, ItemCategory::WEAPON->value],
            ['Leather Armor', 0, 3, 3, ItemCategory::ARMOR->value],
            ['Breastplate', 0, 6, -4, ItemCategory::ARMOR->value],
            ['Plate Armor', 0, 8, 0, ItemCategory::ARMOR->value],
            ['Heavy Armor', 2, 10, -15, ItemCategory::ARMOR->value],
            ['Broken Shield', 0, 2, -2, ItemCategory::SHIELD->value],
            ['Shield', 2, 4, 5, ItemCategory::SHIELD->value],
            ['Iron Shield', 1, 6, -10, ItemCategory::SHIELD->value],
            ['Heavy Shield', -3, 8, -8, ItemCategory::SHIELD->value],
            ['Gloves', 2, 1, 5, ItemCategory::GLOVES->value],
            ['Leather Bracers', 1, 2, -10, ItemCategory::GLOVES->value],
            ['Metallic Gloves ', 2, 4, 0, ItemCategory::GLOVES->value],
            ['Blackguard\'s Gauntlets', 4, 4, -15, ItemCategory::GLOVES->value],
        ];

        foreach ($classes as $class) {
            $this->connection->executeStatement($sql, $class);
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('TRUNCATE item_schemas');
    }
}
