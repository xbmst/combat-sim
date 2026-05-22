<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Application\Dto\GetSetupDataResponse;
use App\Domain\Port\GameConfigRepositoryInterface;
use App\Domain\ValueObject\CharacterLoadout;

class GetSetupDataQueryHandler
{
    public function __construct(private GameConfigRepositoryInterface $gameConfigRepository)
    {
    }

    public function __invoke(GetSetupDataQuery $query): GetSetupDataResponse
    {
        return new GetSetupDataResponse(
            $this->gameConfigRepository->getAllClasses(),
            $this->gameConfigRepository->getAllItems(),
            [
                'max_items' => CharacterLoadout::MAX_ITEMS,
            ]
        );
    }
}
