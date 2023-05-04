<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DiceGraphic.
 */
class DiceGraphicTest extends TestCase
{
    /**
     * Construct dice graphic object without arguments and check default values.
     */
    public function testCreateDiceGraphicCheckDefaults()
    {
        $graphic = new DiceGraphic();
        $graphic->roll();

        $this->assertInstanceOf("\App\Dice\DiceGraphic", $graphic);

        $res = $graphic->getAsString();
        $exp = "";

        $this->assertNotEquals($exp, $res);
    }
}