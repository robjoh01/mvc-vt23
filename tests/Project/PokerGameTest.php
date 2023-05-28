<?php

namespace App\PokerGame;

use Exception;

use App\Card\Card;
use App\Game\PokerGame;

use PHPUnit\Framework\TestCase;

class PokerGameTest extends TestCase
{
    private PokerGame $game;

    protected function setUp(): void
    {
        $this->game = new PokerGame();
    }

    // /**
    //  * @doesNotPerformAssertions
    //  */
    // public function testPopSelectedCard(): void
    // {
    //     $selectedCard = $this->game->popSelectedCard();

    //     $this->assertInstanceOf("\App\Card\Card", $selectedCard);
    // }

    // /**
    //  * @doesNotPerformAssertions
    //  */
    // public function testPeekSelectedCard(): void
    // {
    //     $selectedCard = $this->game->peekSelectedCard();

    //     $this->assertInstanceOf("\App\Card\Card", $selectedCard);
    // }

    // /**
    //  * @doesNotPerformAssertions
    //  */
    // public function testGetBoardElement(): void
    // {
    //     $row = 0;
    //     $column = 0;

    //     $boardElement = $this->game->getBoardElement($row, $column);

    //     $this->assertNull($boardElement);
    // }

    // /**
    //  * @doesNotPerformAssertions
    //  */
    // public function testSetBoardElement(): void
    // {
    //     $row = 0;
    //     $column = 0;
    //     $card = new Card();

    //     $this->game->setBoardElement($row, $column, $card);

    //     $boardElement = $this->game->getBoardElement($row, $column);

    //     $this->assertSame($card, $boardElement);
    // }

    // /**
    //  * @doesNotPerformAssertions
    //  */
    // public function testHasBoardElement(): void
    // {
    //     $row = 0;
    //     $column = 0;

    //     $hasBoardElement = $this->game->hasBoardElement($row, $column);

    //     $this->assertFalse($hasBoardElement);
    // }

    // /**
    //  * @doesNotPerformAssertions
    //  */
    // public function testGetRowAndColumnPoints(): void
    // {
    //     $row = 0;
    //     $column = 0;

    //     $points = $this->game->getRowAndColumnPoints($row, $column);

    //     $this->assertEquals(0, $points);
    // }

    // /**
    //  * @doesNotPerformAssertions
    //  */
    // public function testGetRowPoints(): void
    // {
    //     $row = 0;

    //     $points = $this->game->getRowPoints($row);

    //     $this->assertGreaterThanOrEqual(0, $points);
    // }

    // /**
    //  * @doesNotPerformAssertions
    //  */
    // public function testGetColumnPoints(): void
    // {
    //     $column = 0;

    //     $points = $this->game->getColumnPoints($column);

    //     $this->assertGreaterThanOrEqual(0, $points);
    // }

    // /**
    //  * @doesNotPerformAssertions
    //  */
    // public function testGetTotalPoints(): void
    // {
    //     $points = $this->game->getTotalPoints();

    //     $this->assertGreaterThanOrEqual(0, $points);
    // }

    // /**
    //  * @doesNotPerformAssertions
    //  */
    // public function testGetData(): void
    // {
    //     $data = $this->game->getData();

    //     $this->assertIsArray($data);
    //     $this->assertArrayHasKey('board', $data);
    //     $this->assertArrayHasKey('selectedCard', $data);
    // }
}
