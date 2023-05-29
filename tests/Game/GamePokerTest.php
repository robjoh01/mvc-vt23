<?php

namespace App\Game;

use Exception;

use App\Card\Card;
use App\Poker\PokerRanker;
use App\Poker\PokerRank;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class GamePoker.
 */
class GamePokerTest extends TestCase
{
    public function testPopSelectedCard(): void
    {
        $game = new PokerGame();

        $selectedCard = $game->peekSelectedCard();

        $this->assertInstanceOf(Card::class, $selectedCard);

        $game->popSelectedCard();

        $poppedCard = $game->peekSelectedCard();

        $this->assertInstanceOf(Card::class, $poppedCard);
        $this->assertNotSame($selectedCard, $poppedCard);
    }

    public function testSetAndGetBoardElement(): void
    {
        $game = new PokerGame();

        $card = new Card("♥", "A", 14);

        $game->setBoardElement(2, 3, $card);

        $retrievedCard = $game->getBoardElement(2, 3);

        $this->assertSame($card, $retrievedCard);
    }

    public function testHasBoardElement(): void
    {
        $game = new PokerGame();

        $this->assertFalse($game->hasBoardElement(0, 0));

        $card = new Card("♠", "K", 13);

        $game->setBoardElement(0, 0, $card);

        $this->assertTrue($game->hasBoardElement(0, 0));
    }

    public function testIsCompleted(): void
    {
        $game = new PokerGame();

        $this->assertFalse($game->isCompleted());

        // Fill the board with cards
        for ($row = 0; $row < 5; $row++) {
            for ($column = 0; $column < 5; $column++) {
                $game->setBoardElement($row, $column, new Card("♣", "2", 2));
            }
        }

        $this->assertTrue($game->isCompleted());
    }

    public function testGetRowHandRank(): void
    {
        $game = new PokerGame();

        // Set up a row with a straight flush
        $game->setBoardElement(2, 0, new Card("♥", "10", 10));
        $game->setBoardElement(2, 1, new Card("♥", "J", 11));
        $game->setBoardElement(2, 2, new Card("♥", "Q", 12));
        $game->setBoardElement(2, 3, new Card("♥", "K", 13));
        $game->setBoardElement(2, 4, new Card("♥", "A", 14));

        $expectedRank = PokerRank::ROYAL_FLUSH;

        $this->assertEquals($expectedRank, $game->getRowHandRank(2));
    }

    public function testGetColumnHandRank(): void
    {
        $game = new PokerGame();

        // Set up a column with a four of a kind
        $game->setBoardElement(0, 2, new Card("♥", "A", 14));
        $game->setBoardElement(1, 2, new Card("♦", "A", 14));
        $game->setBoardElement(2, 2, new Card("♣", "A", 14));
        $game->setBoardElement(3, 2, new Card("♠", "A", 14));

        $expectedRank = PokerRank::FOUR_OF_A_KIND;

        $this->assertEquals($expectedRank, $game->getColumnHandRank(2));
    }

    public function testGetTotalHandRankPoints(): void
    {
        $game = new PokerGame();

        // Set up a board with various hands
        $game->setBoardElement(0, 2, new Card("♥", "10", 10));
        $game->setBoardElement(0, 2, new Card("♥", "J", 11));
        $game->setBoardElement(0, 2, new Card("♥", "Q", 12));
        $game->setBoardElement(0, 2, new Card("♥", "K", 13));
        $game->setBoardElement(0, 2, new Card("♥", "A", 14));

        $game->setBoardElement(1, 2, new Card("♠", "A", 14));

        $game->setBoardElement(3, 1, new Card("♣", "7", 7));
        $game->setBoardElement(3, 2, new Card("♦", "7", 7));
        $game->setBoardElement(3, 3, new Card("♥", "7", 7));

        $expectedPoints = 12;

        $this->assertEquals($expectedPoints, $game->getTotalHandRankPoints());
    }

    public function testGetPointsFromHandRank(): void
    {
        $game = new PokerGame();

        $this->assertEquals(0, $game->getPointsFromHandRank(PokerRank::HIGH_CARD));
        $this->assertEquals(2, $game->getPointsFromHandRank(PokerRank::ONE_PAIR));
        $this->assertEquals(5, $game->getPointsFromHandRank(PokerRank::TWO_PAIR));
        $this->assertEquals(10, $game->getPointsFromHandRank(PokerRank::THREE_OF_A_KIND));
        $this->assertEquals(15, $game->getPointsFromHandRank(PokerRank::STRAIGHT));
        $this->assertEquals(20, $game->getPointsFromHandRank(PokerRank::FLUSH));
        $this->assertEquals(25, $game->getPointsFromHandRank(PokerRank::FULL_HOUSE));
        $this->assertEquals(50, $game->getPointsFromHandRank(PokerRank::FOUR_OF_A_KIND));
        $this->assertEquals(75, $game->getPointsFromHandRank(PokerRank::STRAIGHT_FLUSH));
        $this->assertEquals(100, $game->getPointsFromHandRank(PokerRank::ROYAL_FLUSH));
    }

    public function testGetRowAndColumnPoints(): void
    {
        $game = new PokerGame();

        $card = new Card("♣", "8", 8);

        $game->setBoardElement(1, 3, $card);

        $expectedPoints = $card->getScore();
        $this->assertEquals($expectedPoints, $game->getRowAndColumnPoints(1, 3));

        $game->setBoardElement(1, 3, null);

        $expectedPoints = 0;
        $this->assertEquals($expectedPoints, $game->getRowAndColumnPoints(1, 3));
    }

    public function testGetRowPoints(): void
    {
        $game = new PokerGame();

        $game->setBoardElement(2, 1, new Card("♥", "2", 2));
        $game->setBoardElement(2, 2, new Card("♥", "3", 3));
        $game->setBoardElement(2, 3, new Card("♥", "4", 4));

        $expectedPoints = 2 + 3 + 4;

        $this->assertEquals($expectedPoints, $game->getRowPoints(2));
    }

    public function testGetColumnPoints(): void
    {
        $game = new PokerGame();

        $game->setBoardElement(1, 4, new Card("♣", "7", 7));
        $game->setBoardElement(2, 4, new Card("♣", "8", 8));
        $game->setBoardElement(3, 4, new Card("♣", "9", 9));

        $expectedPoints = 7 + 8 + 9;

        $this->assertEquals($expectedPoints, $game->getColumnPoints(4));
    }

    public function testGetTotalPoints(): void
    {
        $game = new PokerGame();

        $game->setBoardElement(1, 1, new Card("♦", "5", 5));
        $game->setBoardElement(2, 2, new Card("♦", "6", 6));
        $game->setBoardElement(3, 3, new Card("♦", "7", 7));

        $expectedPoints = 5 + 6 + 7;

        $this->assertEquals($expectedPoints, $game->getTotalPoints());
    }

    public function testGetData(): void
    {
        $game = new PokerGame();

        $data = $game->getData();

        $this->assertArrayHasKey('board', $data);
        $this->assertArrayHasKey('selectedCard', $data);

        $this->assertIsArray($data['board']);
        $this->assertInstanceOf(Card::class, $data['selectedCard']);
    }
}