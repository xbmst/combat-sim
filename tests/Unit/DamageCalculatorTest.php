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
    public function test_items_modify_strike_stats_without_mutating_warriors(): void
    {
        $item1 = new Item('id1', 'Sword', 'weapon', 10, 0, 3);
        $item2 = new Item('id2', 'Shield', 'shield', 0, 5, 0);

        $attacker = new Warrior('Attacker', new Stats(100, 60, 20, 10, 10), [$item1]);
        $defender = new Warrior('Defender', new Stats(100, 100, 20, 10, 10), [$item2]);

        $attackerModifier = new AttackerItemStatsModifier();
        $defenderModifier = new DefenderItemStatsModifier();

        $context = StrikeContext::fromWarriors($attacker, $defender);
        $context = $attackerModifier->apply($context);
        $context = $defenderModifier->apply($context);

        self::assertSame(30, $context->attackerStats->attack);
        self::assertSame(10, $context->attackerStats->defense);
        self::assertSame(13, $context->attackerStats->agility);
        self::assertSame(125, $context->attackerStats->maxHp);
        self::assertSame(60, $context->attackerStats->currentHp, 'Increasing max HP must not heal the warrior');

        self::assertSame(20, $context->defenderStats->attack);
        self::assertSame(15, $context->defenderStats->defense);

        self::assertSame(20, $attacker->stats->attack);
        self::assertSame(10, $attacker->stats->agility);
        self::assertSame(100, $attacker->stats->maxHp);
        self::assertSame(10, $defender->stats->defense);
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
        self::assertSame(['[Math: 35 attack vs 15 defense]'], $result->logs);
    }

    public function test_repeated_strikes_do_not_compound_item_modifiers(): void
    {
        $attacker = new Warrior(
            'Attacker',
            new Stats(100, 100, 20, 10, 10),
            [new Item('id1', 'Sword', 'weapon', 5)],
        );
        $defender = new Warrior(
            'Defender',
            new Stats(100, 100, 20, 10, 10),
            [new Item('id2', 'Axe', 'weapon', 5)],
        );

        $calculator = new DamageCalculator([
            new AttackerItemStatsModifier(),
            new DefenderItemStatsModifier(),
            new BaseStrikeModifier(),
        ]);

        $firstStrike = $calculator->calculateStrike($attacker, $defender);
        $secondStrike = $calculator->calculateStrike($attacker, $defender);
        $firstCounterStrike = $calculator->calculateStrike($defender, $attacker);
        $secondCounterStrike = $calculator->calculateStrike($defender, $attacker);

        self::assertSame(15, $firstStrike->damageToDeal);
        self::assertSame($firstStrike->damageToDeal, $secondStrike->damageToDeal);
        self::assertSame(15, $firstCounterStrike->damageToDeal);
        self::assertSame($firstCounterStrike->damageToDeal, $secondCounterStrike->damageToDeal);
        self::assertSame(20, $attacker->stats->attack);
        self::assertSame(20, $defender->stats->attack);
    }
}
