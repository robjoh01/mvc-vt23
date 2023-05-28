<?php

namespace App\Poker;

use PHPUnit\Framework\TestCase;
use App\Card\Card;
use App\Card\CardHand;

/**
 * Test cases for class PokerRanker.
 */
class PokerRankerTest extends TestCase
{
    public function testHighCardRank(): void
    {
        $hand = new CardHand();

        $hand->addCard(new Card('♦', 'A', 14));
        $hand->addCard(new Card('♠', '9', 9));
        $hand->addCard(new Card('♠', '7', 7));
        $hand->addCard(new Card('♠', '5', 5));
        $hand->addCard(new Card('♦', '3', 3));

        $rank = PokerRanker::getHandRank($hand);

        $this->assertEquals(PokerRank::HIGH_CARD, $rank);
    }

    public function testOnePairRank(): void
    {
        $hand = new CardHand();

        $hand->addCard(new Card('♠', 'A', 14));
        $hand->addCard(new Card('♥', 'A', 14));
        $hand->addCard(new Card('♣', 'Q', 12));
        $hand->addCard(new Card('♦', 'J', 11));
        $hand->addCard(new Card('♠', '10', 10));

        $rank = PokerRanker::getHandRank($hand);

        $this->assertEquals(PokerRank::ONE_PAIR, $rank);
    }

    public function testTwoPairRank(): void
    {
        $hand = new CardHand();

        $hand->addCard(new Card('♠', 'A', 14));
        $hand->addCard(new Card('♥', 'A', 14));
        $hand->addCard(new Card('♣', 'Q', 12));
        $hand->addCard(new Card('♦', 'Q', 12));
        $hand->addCard(new Card('♠', '10', 10));

        $rank = PokerRanker::getHandRank($hand);

        $this->assertEquals(PokerRank::TWO_PAIR, $rank);
    }

    public function testThreeOfAKindRank(): void
    {
        $hand = new CardHand();

        $hand->addCard(new Card('♠', 'A', 14));
        $hand->addCard(new Card('♥', 'A', 14));
        $hand->addCard(new Card('♣', 'A', 14));
        $hand->addCard(new Card('♦', 'J', 11));
        $hand->addCard(new Card('♠', '10', 10));

        $rank = PokerRanker::getHandRank($hand);

        $this->assertEquals(PokerRank::THREE_OF_A_KIND, $rank);
    }

    public function testStraightRank(): void
    {
        $hand = new CardHand();

        $hand->addCard(new Card('♠', 'A', 14));
        $hand->addCard(new Card('♥', 'K', 13));
        $hand->addCard(new Card('♣', 'Q', 12));
        $hand->addCard(new Card('♦', 'J', 11));
        $hand->addCard(new Card('♠', '10', 10));

        $rank = PokerRanker::getHandRank($hand);

        $this->assertEquals(PokerRank::STRAIGHT, $rank);
    }

    public function testStraight2Rank(): void
    {
        $hand = new CardHand();

        $hand->addCard(new Card('♣', '2', 2));
        $hand->addCard(new Card('♦', '3', 3));
        $hand->addCard(new Card('♥', '4', 4));
        $hand->addCard(new Card('♠', '5', 5));
        $hand->addCard(new Card('♣', '6', 6));

        $rank = PokerRanker::getHandRank($hand);

        $this->assertEquals(PokerRank::STRAIGHT, $rank);
    }

    public function testFlushRank(): void
    {
        $hand = new CardHand();

        $hand->addCard(new Card('♠', '7', 7));
        $hand->addCard(new Card('♠', '6', 6));
        $hand->addCard(new Card('♠', '2', 2));
        $hand->addCard(new Card('♠', 'K', 11));
        $hand->addCard(new Card('♠', '10', 10));

        $rank = PokerRanker::getHandRank($hand);

        $this->assertEquals(PokerRank::FLUSH, $rank);
    }

    public function testFullHouseRank(): void
    {
        $hand = new CardHand();

        $hand->addCard(new Card('♣', 'A', 14));
        $hand->addCard(new Card('♠', 'A', 14));
        $hand->addCard(new Card('♦', '10', 10));
        $hand->addCard(new Card('♠', '10', 10));
        $hand->addCard(new Card('♥', '10', 10));

        $rank = PokerRanker::getHandRank($hand);

        $this->assertEquals(PokerRank::FULL_HOUSE, $rank);
    }

    public function testFourOfAKindRank(): void
    {
        $hand = new CardHand();

        $hand->addCard(new Card('♠', '3', 3));
        $hand->addCard(new Card('♦', 'K', 13));
        $hand->addCard(new Card('♣', 'K', 13));
        $hand->addCard(new Card('♥', 'K', 13));
        $hand->addCard(new Card('♠', 'K', 13));

        $rank = PokerRanker::getHandRank($hand);

        $this->assertEquals(PokerRank::FOUR_OF_A_KIND, $rank);
    }

    public function testStraightFlushRank(): void
    {
        $hand = new CardHand();

        $hand->addCard(new Card('♣', '8', 8));
        $hand->addCard(new Card('♣', '7', 7));
        $hand->addCard(new Card('♣', '6', 6));
        $hand->addCard(new Card('♣', '5', 5));
        $hand->addCard(new Card('♣', '4', 4));

        $rank = PokerRanker::getHandRank($hand);

        $this->assertEquals(PokerRank::STRAIGHT_FLUSH, $rank);
    }

    public function testRoyalFlushRank(): void
    {
        $hand = new CardHand();

        $hand->addCard(new Card('♥', 'A', 14));
        $hand->addCard(new Card('♥', 'K', 13));
        $hand->addCard(new Card('♥', 'Q', 12));
        $hand->addCard(new Card('♥', 'J', 11));
        $hand->addCard(new Card('♥', '10', 10));

        $valueArray = $hand->getValueArray();

         $this->assertTrue(in_array("10", $valueArray));
         $this->assertTrue(in_array("J", $valueArray));
         $this->assertTrue(in_array("Q", $valueArray));
         $this->assertTrue(in_array("K", $valueArray));
         $this->assertTrue(in_array("A", $valueArray));

        $rank = PokerRanker::getHandRank($hand);

        $this->assertEquals(PokerRank::ROYAL_FLUSH, $rank);
    }
}
