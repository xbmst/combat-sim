<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Domain\Model\Stats;
use App\Domain\Model\Warrior;
use App\Domain\Pipeline\Modifier\AttackerItemStatsModifier;
use App\Domain\Pipeline\Modifier\BaseStrikeModifier;
use App\Domain\Pipeline\Modifier\DefenderItemStatsModifier;
use App\Domain\Service\DamageCalculator;
use App\Domain\ValueObject\Item;
use App\Domain\ValueObject\StrikeContext;
use PHPUnit\Framework\TestCase;

class DamageCalculatorTest extends TestCase
{
    public function test_items_affect_warrior_stats(): void
    {
        $item1 = new Item('id1', 'Sword', 'weapon', 10, 0, 0);
        $item2 = new Item('id2', 'Shield', 'shield', 0, 5, 0);

        $attacker = new Warrior('Attacker', new Stats(100, 100, 20, 10, 10), [$item1]);
        $defender = new Warrior('Defender', new Stats(100, 100, 20, 10, 10), [$item2]);

        $attackerModifier = new AttackerItemStatsModifier();
        $defenderModifier = new DefenderItemStatsModifier();

        $context = new StrikeContext($attacker, $defender, 0);
        $context = $attackerModifier->apply($context);
        $context = $defenderModifier->apply($context);

        self::assertEquals(30, $context->attacker->stats->attack, 'Attacker attack should be 20 + 10');
        self::assertEquals(10, $context->attacker->stats->defense, 'Attacker defense unchanged');

        self::assertEquals(20, $context->defender->stats->attack, 'Defender attack unchanged');
        self::assertEquals(15, $context->defender->stats->defense, 'Defender defense should be 10 + 5');
    }

    public function test_damage_calculation_formula(): void
    {
        $item1 = new Item('id1', 'Sword', 'weapon', 15, 0, 0);
        $item2 = new Item('id2', 'Shield', 'shield', 0, 5, 0);

        // Attacker base attack 20 + item 15 = 35
        $attacker = new Warrior('Attacker', new Stats(100, 100, 20, 10, 10), [$item1]);
        // Defender base defense 10 + item 5 = 15
        $defender = new Warrior('Defender', new Stats(100, 100, 20, 10, 10), [$item2]);

        $calculator = new DamageCalculator([
            new AttackerItemStatsModifier(),
            new DefenderItemStatsModifier(),
            new BaseStrikeModifier(),
        ]);

        $result = $calculator->calculateStrike($attacker, $defender);

        // Formula: (20 + 15) - (10 + 5) = 35 - 15 = 20
        self::assertEquals(20, $result->damageToDeal, 'Damage should be equals (character basic attack + items attack sum) - (opponent basic defense + item defense sum)');
    }
}
