<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Domain\ValueObject\HeroLoadout;
use Doctrine\DBAL\Connection;

class GetSetupDataQueryHandler
{
    public function __construct(private Connection $connection)
    {
    }

    public function __invoke(GetSetupDataQuery $query): GetSetupDataResponse
    {
        $classes = $this->connection->fetchAllAssociative(
            'SELECT id, name, base_hp as baseHp, base_attack as baseAttack FROM class_schemas'
        );
        // TODO: validate
        $items = $this->connection->fetchAllAssociative(
            'SELECT id, modifier_hp as "modifierHp", modifier_attack as "modifierAttack" FROM item_schemas'
        );

        return new GetSetupDataResponse(
            $classes,
            $items,
            [
                'max_items' => HeroLoadout::MAX_ITEMS,
            ]
        );
    }
}
