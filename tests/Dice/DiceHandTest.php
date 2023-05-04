<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DiceHand.
 */
class DiceHandTest extends TestCase
{
    /**
     * Construct dice hand object without arguments and check default values.
     */
    public function testCreateDiceCheckDefaults()
    {
        $hand = new DiceHand();

        $this->assertInstanceOf("\App\Dice\DiceHand", $hand);

        $res = $hand->getNumberDices();
        $exp = 0;

        $this->assertEquals($exp, $res);
    }

    /**
     * Construct dice hand object with arguments and check values.
     */
    public function testDiceHandRoll()
    {
        $hand = new DiceHand();

        $hand->add(new Dice());

        $res = $hand->getValues();
        $exp = [ -1 ];

        $this->assertEquals($exp, $res);

        $hand->roll();

        $res = $hand->getValues();

        $this->assertNotEquals($exp, $res);

        $res = $hand->getAsString();
        $exp = [ "[-1]" ];

        $this->assertNotEquals($exp, $res);
    }
}