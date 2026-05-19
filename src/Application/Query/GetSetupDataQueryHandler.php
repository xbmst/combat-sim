<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Application\Dto\GetSetupDataResponse;
use App\Domain\ValueObject\CharacterLoadout;
use Doctrine\DBAL\Connection;

class GetSetupDataQueryHandler
{
    public function __construct(private Connection $connection)
    {
    }

    public function __invoke(GetSetupDataQuery $query): GetSetupDataResponse
    {
        $classes = $this->connection->fetchAllAssociative(
            'SELECT id, name, base_hp as baseHp, base_attack as baseAttack, base_defense as baseDefense, base_agility as baseAgility FROM class_schemas'
        );
        // TODO: validate
        $items = $this->connection->fetchAllAssociative(
            'SELECT id, name, modifier_attack as modifierAttack, modifier_defense as modifierDefense, modifier_agility as modifierAgility FROM item_schemas'
        );

        return new GetSetupDataResponse(
            $classes,
            $items,
            [
                'max_items' => CharacterLoadout::MAX_ITEMS,
            ]
        );
    }
}
