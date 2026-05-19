<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Domain\Model\Battle;
use App\Domain\Model\Stats;
use App\Domain\Model\Warrior;
use App\Domain\Service\DiceRoller;
use PHPUnit\Framework\TestCase;

class BattleTest extends TestCase
{
    public function test_dodge_attack_if_agility_gt_dice(): void
    {
        $character = new Warrior('Char', new Stats(100, 100, 10, 10, 50), []);
        $opponent = new Warrior('Opp', new Stats(100, 100, 10, 10, 10), []);

        $battle = new Battle('id1', 'id1', $character, $opponent, 1);

        $diceRollerMock = $this->createMock(DiceRoller::class);

        // Dice roll 49, agility 50 -> dodged
        $diceRollerMock->method('roll')->willReturn(49);
        self::assertTrue($battle->isAttackDodged($diceRollerMock, $character), 'Should dodge if dice (49) <= agility (50)');

        // Dice roll 50, agility 50 -> dodged
        $diceRollerMock = $this->createMock(DiceRoller::class);
        $diceRollerMock->method('roll')->willReturn(50);
        self::assertTrue($battle->isAttackDodged($diceRollerMock, $character), 'Should dodge if dice (50) <= agility (50)');

        // Dice roll 51, agility 50 -> not dodged
        $diceRollerMock = $this->createMock(DiceRoller::class);
        $diceRollerMock->method('roll')->willReturn(51);
        self::assertFalse($battle->isAttackDodged($diceRollerMock, $character), 'Should not dodge if dice (51) > agility (50)');
    }

    public function test_game_is_over_when_someone_dies(): void
    {
        // Character dead
        $character = new Warrior('Char', new Stats(100, 0, 10, 10, 10), []);
        $opponent = new Warrior('Opp', new Stats(100, 100, 10, 10, 10), []);
        $battle = new Battle('id1', 'id1', $character, $opponent, 1);

        self::assertTrue($battle->isCharacterDead(), 'Character should be dead');
        self::assertFalse($battle->isOpponentDead(), 'Opponent should not be dead');

        // Opponent dead
        $character2 = new Warrior('Char', new Stats(100, 100, 10, 10, 10), []);
        $opponent2 = new Warrior('Opp', new Stats(100, 0, 10, 10, 10), []);
        $battle2 = new Battle('id2', 'id2', $character2, $opponent2, 1);

        self::assertFalse($battle2->isCharacterDead(), 'Character should not be dead');
        self::assertTrue($battle2->isOpponentDead(), 'Opponent should be dead');
    }
}
