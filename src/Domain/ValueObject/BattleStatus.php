<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

enum BattleStatus: string
{
    case NEXT_TURN = 'next_turn';
    case BATTLE_WON = 'battle_won';
    case GAME_WON = 'game_won';
    case GAME_OVER = 'game_over';
}
