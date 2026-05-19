<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Domain\Model\Stats;
use App\Domain\Model\Warrior;
use App\Domain\Service\TurnPicker;
use PHPUnit\Framework\TestCase;

class TurnPickerTest extends TestCase
{
    public function test_higher_agility_starts_first(): void
    {
        $turnPicker = new TurnPicker();

        $slowWarrior = new Warrior('LowAgility', new Stats(100, 100, 10, 10, 5), []);
        $fastWarrior = new Warrior('HighAgility', new Stats(100, 100, 10, 10, 15), []);

        $result1 = $turnPicker->pick($slowWarrior, $fastWarrior);
        self::assertSame($fastWarrior, $result1[0], 'HighAgility warrior should be first');
        self::assertSame($slowWarrior, $result1[1], 'LowAgility warrior should be second');

        $result2 = $turnPicker->pick($fastWarrior, $slowWarrior);
        self::assertSame($fastWarrior, $result2[0], 'HighAgility warrior should be first');
        self::assertSame($slowWarrior, $result2[1], 'LowAgility warrior should be second');
    }
}
