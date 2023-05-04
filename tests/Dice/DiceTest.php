<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class DiceTest extends TestCase
{
    /**
     * Construct dice object without arguments and check default values.
     */
    public function testCreateDiceCheckDefaults(): void
    {
        $die = new Dice();

        $this->assertInstanceOf("\App\Dice\Dice", $die);

        $res = $die->getValue();
        $exp = -1;

        $this->assertEquals($exp, $res);
    }

    /**
     * Roll the dice and check its outcome
     */
    public function testRollDiceCheckOutcome(): void
    {
        $die = new Dice();
        $res = $die->roll();

        $this->assertIsInt($res);

        $res = $die->getValue();

        $this->assertTrue($res >= 1);
        $this->assertTrue($res <= 6);

        $res = $die->getAsString();
        $exp = "[{$die->getValue()}]";

        $this->assertEquals($exp, $res);
    }
}