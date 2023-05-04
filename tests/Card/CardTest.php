<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    /**
     * Construct card object without arguments and check default values.
     */
    public function testCreateCardCheckDefaults(): void
    {
        $card = new Card("♥", "A", 1);
        $this->assertInstanceOf("\App\Card\Card", $card);
    }

    /**
     * Construct card object without arguments and check default values.
     */
    public function testGetProperties(): void
    {
        $card = new Card("♥", "A", 1);

        $res = $card->getSuit();
        $exp = "♥";
        $this->assertEquals($exp, $res);

        $res = $card->getValue();
        $exp = "A";
        $this->assertEquals($exp, $res);

        $res = $card->getScore();
        $exp = 1;
        $this->assertEquals($exp, $res);

        $res = $card->getAsString();
        $exp = "A♥";
        $this->assertEquals($exp, $res);
    }
}