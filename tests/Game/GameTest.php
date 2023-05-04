<?php

namespace App\Game;

use Exception;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Game.
 */
class GameTest extends TestCase
{
    /**
     * Construct game object without arguments and check default values.
     */
    public function testCreateGameCheckDefaults(): void
    {
        $game = new Game();

        $this->assertInstanceOf("\App\Game\Game", $game);
    }

    /**
     * Construct game object without arguments and check default values.
     */
    public function testGetGameProperties(): void
    {
        $game = new Game();

        $playerHand = $game->getPlayerHand();

        $this->assertInstanceOf("\App\Card\CardHand", $playerHand);

        $aiHand = $game->getAIHand();

        $this->assertInstanceOf("\App\Card\CardHand", $aiHand);

        $deck = $game->getDeck();

        $this->assertInstanceOf("\App\Card\DeckOfCards", $deck);
    }

    /**
     * Construct game object without arguments and check default values.
     */
    public function testGameDrawEmptyDeck(): void
    {
        $game = new Game();

        $deck = $game->getDeck();
        $cardCount = $deck->getCardCount();

        for ($i = 0; $i <= $cardCount; $i++) {
            $deck->draw();
        }

        $this->assertTrue($deck->isEmpty());

        $this->expectException(Exception::class);
        $game->drawPlayerCard();
    }

    /**
     * Construct game object without arguments and check default values.
     */
    public function testGameDrawCard(): void
    {
        $game = new Game();

        $game->drawPlayerCard();

        $hand = $game->getPlayerHand();

        $res = $hand->getCardCount();
        $exp = 2;

        $this->assertEquals($exp, $res);
    }

    /**
     * Construct game object without arguments and check default values.
     */
    public function testGameRender(): void
    {
        $game = new Game();
        $renderData = $game->render();

        $playerCards = $renderData["player_cards"];

        $exp = 0;
        $res = count($playerCards);

        $this->assertGreaterThan($exp, $res);
    }

    /**
     * Construct game object without arguments and check default values.
     */
    public function testGameEnd(): void
    {
        $game = new Game();
        $endData = $game->end();

        $this->assertFalse($endData["is_player_winner"]);
    }

    /**
     * Construct game object without arguments and check default values.
     */
    public function testGameAIDecision(): void
    {
        $game = new Game();
        $aiHand = $game->getAIHand();

        $aiBefore = $aiHand->getCardCount();

        $game->aiDecision();

        $exp = $aiBefore;
        $res = $aiHand->getCardCount();

        $this->assertNotEquals($exp, $res);
    }
}