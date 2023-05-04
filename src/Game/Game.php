<?php

namespace App\Game;

use Exception;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;

class Game
{
    private DeckOfCards $deck;
    private CardHand $playerHand;
    private CardHand $aiHand;

    public function __construct()
    {
        $this->deck = new DeckOfCards();
        $this->playerHand = new CardHand();
        $this->aiHand = new CardHand();

        $this->deck->shuffle();

        /** @var Card */
        $newCard = $this->deck->draw();

        $this->playerHand->addCard($newCard);
    }

    /**
     * Converts game's data into an array of mixed values (specific for playing screen).
     * @return mixed[]
     */
    public function render(): array
    {
        $data = [
            "deck_of_cards" => $this->deck->getCards(),
            "player_cards" => $this->playerHand->getCards(),
            "player_score" => $this->playerHand->getScore(),
            "ai_cards" => $this->aiHand->getCards(),
            "ai_score" => $this->aiHand->getScore(),
        ];

        return $data;
    }

    /**
     * Draw one ard from the deck, into the player's hand.
     */
    public function drawPlayerCard(): void
    {
        if ($this->deck->isEmpty()) {
            throw new Exception("Cannot draw with an empty deck.");
        }

        /** @var Card */
        $newCard = $this->deck->draw();

        $this->playerHand->addCard($newCard);
    }

    /**
     * Run AI decision logic, where the AI can decide to draw a card or skip the round.
     */
    public function aiDecision(): void
    {
        if ($this->deck->isEmpty()) {
            throw new Exception("Cannot draw with an empty deck.");
        }

        $isPickingCards = true;

        while ($isPickingCards) {
            /** @var Card */
            $newCard = $this->deck->draw();

            $this->aiHand->addCard($newCard);

            $aiScore = $this->aiHand->getScore();

            if ($aiScore > 21) {
                break;
            }

            $isPickingCards = rand(0, 100) < ($aiScore >= 17 ? 10 : 50); // Randomize boolean
        }
    }

    /**
     * Converts game's data into an array of mixed values (specific for end screen).
     * @return mixed[]
     */
    public function end(): array
    {
        $playerScore = $this->playerHand->getScore();
        $aiScore = $this->aiHand->getScore();
        $isPlayerWinner = $playerScore <= 21 && $aiScore > 21;
        $isAIWinner = $aiScore <= 21 && $playerScore > 21;

        $data = [
            "deck_of_cards" => $this->deck->getCards(),
            "player_cards" => $this->playerHand->getCards(),
            "player_score" => $playerScore,
            "ai_cards" => $this->aiHand->getCards(),
            "ai_score" => $aiScore,
            "is_player_winner" => $isPlayerWinner,
            "is_ai_winner" => $isAIWinner,
        ];

        return $data;
    }

    /**
     * Get deck of cards.
     */
    public function getDeck(): DeckOfCards
    {
        return $this->deck;
    }

    /**
     * Get player hand.
     */
    public function getPlayerHand(): CardHand
    {
        return $this->playerHand;
    }

    /**
     * Get AI hand.
     */
    public function getAIHand(): CardHand
    {
        return $this->aiHand;
    }
}
