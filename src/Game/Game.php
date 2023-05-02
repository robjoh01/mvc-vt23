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

        $this->playerHand->add($newCard);
    }

    /** @return mixed[] */
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

    public function drawCard(): void
    {
        if ($this->deck->isEmpty()) {
            throw new Exception("Cannot draw with an empty deck.");
        }

        /** @var Card */
        $newCard = $this->deck->draw();

        $this->playerHand->add($newCard);
    }

    public function aiDecision(): void
    {
        if ($this->deck->isEmpty()) {
            throw new Exception("Cannot draw with an empty deck.");
        }

        $isPickingCards = true;

        while ($isPickingCards) {
            /** @var Card */
            $newCard = $this->deck->draw();

            $this->aiHand->add($newCard);

            $aiScore = $this->aiHand->getScore();

            if ($aiScore > 21) {
                break;
            }

            $isPickingCards = rand(0, 100) < ($aiScore >= 17 ? 10 : 50); // Randomize boolean
        }
    }

    /** @return mixed[] */
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

    public function getDeck(): DeckOfCards
    {
        return $this->deck;
    }

    public function getPlayerHand(): CardHand
    {
        return $this->playerHand;
    }

    public function getAIHand(): CardHand
    {
        return $this->aiHand;
    }
}
