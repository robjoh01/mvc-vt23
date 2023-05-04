<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardHand.
 */
class CardHandTest extends TestCase
{
    /**
     * Construct card hand object without arguments and check default values.
     */
    public function testCreateCardHandCheckDefaults(): void
    {
        $hand = new CardHand();

        $this->assertInstanceOf("\App\Card\CardHand", $hand);
    }

    /**
     * Construct card hand object with arguments and check values.
     */
    public function testCreateCardHandCheckValues(): void
    {
        $cards = [ new Card("♥", "A", 13) ];
        $hand = new CardHand($cards);

        $res = $hand->getCardCount();
        $exp = 1;

        $this->assertEquals($exp, $res);
    }

    /**
     * Construct card hand object with arguments and check values.
     */
    public function testCardHandClear(): void
    {
        $hand = new CardHand();

        $hand->addCard(new Card("♥", "A", 13));
        $hand->addCard(new Card("♥", "5", 5));
        $hand->addCard(new Card("♥", "7", 7));
        
        $hand->clear();

        $res = $hand->getCardCount();
        $exp = 0;

        $this->assertEquals($exp, $res);
    }

    /**
     * Construct card hand object with arguments and check values.
     */
    public function testGetCardHandProperties(): void
    {
        $hand = new CardHand();

        $card = new Card("♥", "A", 13);

        $hand->addCard($card);
        $hand->addCard(new Card("♥", "5", 5));
        $hand->addCard(new Card("♥", "7", 7));

        $hand->popCard();

        $res = $hand->getCardCount();
        $exp = 2;

        $this->assertEquals($exp, $res);

        $res = count($hand->getAsString());
        $exp = 2;

        $this->assertEquals($exp, $res);

        $res = $hand->getScore();
        $exp = 13 + 5;

        $this->assertEquals($exp, $res);

        $hand->removeCard($card);

        $res = $hand->getScore();
        $exp = 5;

        $this->assertEquals($exp, $res);
    }
}