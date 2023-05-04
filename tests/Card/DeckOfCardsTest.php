<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DeckOfCards.
 */
class DeckOfCardsTest extends TestCase
{
    /**
     * Construct deck of cards object without arguments and check default values.
     */
    public function testCreateDeckOfCardsCheckDefaults(): void
    {
        $deck = new DeckOfCards();

        $this->assertInstanceOf("\App\Card\DeckOfCards", $deck);
    }

    /**
     * Construct deck of cards object with arguments and check values.
     */
    public function testCreateDeckOfCardsCheckCustomCards(): void
    {
        $cards = [ "A♥" ];
        $deck = new DeckOfCards($cards);

        $this->assertInstanceOf("\App\Card\DeckOfCards", $deck);
    }

    /**
     * Construct card object without arguments and check default values.
     */
    public function testGetProperties(): void
    {
        $deck = new DeckOfCards();

        $res = $deck->getCardCount();
        $exp = 52;

        $this->assertEquals($exp, $res);

        $res = $deck->isEmpty();
        $exp = false;

        $this->assertEquals($exp, $res);

        $res = $deck->getSortedCards()[0]->getAsString();
        $exp = "A♥";

        $this->assertEquals($exp, $res);
    }
}