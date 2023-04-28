<?php

namespace App\Game;

use Exception;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Game
{
    public function initialize(SessionInterface $session): void
    {
        $session = $session;

        $deck = new DeckOfCards();
        $deck->shuffle();

        /** @var Card */
        $newCard = $deck->draw();

        $playerHand = new CardHand();
        $playerHand->add($newCard);

        $playerScore = $playerHand->getScore();

        $session->set("deck_of_cards", $deck->getCards());
        $session->set("player_cards", $playerHand->getCards());
        $session->set("player_score", $playerScore);
        $session->set("ai_cards", []);
        $session->set("ai_score", 0);
        $session->set("has_initialize", true);
    }

    /** @return mixed[] */
    public function render(SessionInterface $session): array
    {
        /** @var Card[] */
        $deckOfCards = $session->get("deck_of_cards", null);
        $deck = new DeckOfCards($deckOfCards);

        /** @var Card[] */
        $playerCards = $session->get("player_cards", null);
        $playerHand = new CardHand($playerCards);

        /** @var Card[] */
        $aiCards = $session->get("ai_cards", null);
        $aiHand = new CardHand($aiCards);

        $playerScore = $session->get("player_score", 0);
        $aiScore = $session->get("ai_score", 0);

        $data = [
            "deck_of_cards" => $deck->getCards(),
            "player_cards" => $playerHand->getCards(),
            "player_score" => $playerScore,
            "ai_cards" => $aiHand->getCards(),
            "ai_score" => $aiScore,
        ];

        return $data;
    }

    public function drawCard(SessionInterface $session): void
    {
        /** @var Card[] */
        $deckOfCards = $session->get("deck_of_cards", null);
        $deck = new DeckOfCards($deckOfCards);

        if ($deck->isEmpty()) {
            throw new Exception("Cannot draw with an empty deck.");
        }

        /** @var Card[] */
        $playerCards = $session->get("player_cards", null);
        $playerHand = new CardHand($playerCards);

        /** @var Card */
        $newCard = $deck->draw();

        $playerHand->add($newCard);
        $playerScore = $playerHand->getScore();

        $session->set("deck_of_cards", $deck->getCards());
        $session->set("player_cards", $playerHand->getCards());
        $session->set("player_score", $playerScore);
    }

    public function aiDecision(SessionInterface $session): void
    {
        /** @var Card[] */
        $deckOfCards = $session->get("deck_of_cards", null);
        $deck = new DeckOfCards($deckOfCards);

        if ($deck->isEmpty()) {
            throw new Exception("Cannot draw with an empty deck.");
        }

        /** @var Card[] */
        $aiCards = $session->get("ai_cards", null);
        $aiHand = new CardHand($aiCards);

        $aiScore = $session->get("ai_score", 0);

        $isPickingCards = true;

        while ($isPickingCards) {
            /** @var Card */
            $newCard = $deck->draw();

            $aiHand->add($newCard);

            $aiScore = $aiHand->getScore();

            if ($aiScore > 21) {
                break;
            }

            $isPickingCards = rand(0, 100) < ($aiScore >= 17 ? 10 : 50); // Randomize boolean
        }

        $session->set("deck_of_cards", $deck->getCards());
        $session->set("ai_cards", $aiHand->getCards());
        $session->set("ai_score", $aiScore);
    }

    /** @return mixed[] */ 
    public function end(SessionInterface $session): array
    {
        /** @var Card[] */
        $deckOfCards = $session->get("deck_of_cards", null);
        $deck = new DeckOfCards($deckOfCards);

        /** @var Card[] */
        $playerCards = $session->get("player_cards", null);
        $playerHand = new CardHand($playerCards);

        /** @var Card[] */
        $aiCards = $session->get("ai_cards", null);
        $aiHand = new CardHand($aiCards);

        $playerScore = $session->get("player_score", 0);
        $aiScore = $session->get("ai_score", 0);

        $session->set("has_initialize", false);

        $data = [
            "deck_of_cards" => $deck->getCards(),
            "player_cards" => $playerHand->getCards(),
            "player_score" => $playerScore,
            "ai_cards" => $aiHand->getCards(),
            "ai_score" => $aiScore,
            "is_player_winner" => $playerScore <= 21 && $aiScore > 21,
            "is_ai_winner" => $aiScore <= 21 && $playerScore > 21,
        ];

        return $data;
    }

    public function hasInitialize(SessionInterface $session): bool
    {
        /** @var bool */
        $hasInitialize = $session->get("has_initialize", false);

        return $hasInitialize;
    }

    public function getDeck(SessionInterface $session): DeckOfCards
    {
        /** @var Card[] */
        $deckOfCards = $session->get("deck_of_cards", null);

        return new DeckOfCards($deckOfCards);
    }

    public function getPlayerHand(SessionInterface $session): CardHand
    {
        /** @var Card[] */
        $cards = $session->get("player_cards", null);

        return new CardHand($cards);
    }

    public function getAIHand(SessionInterface $session): CardHand
    {
        /** @var Card[] */
        $cards = $session->get("ai_cards", null);

        return new CardHand($cards);
    }

    public function getPlayerScore(SessionInterface $session): int
    {
        /** @var int */
        $playerScore = $session->get("player_score", 0);

        return $playerScore;
    }

    public function getAIScore(SessionInterface $session): int
    {
        /** @var int */
        $aiScore = $session->get("ai_score", 0);

        return $aiScore;
    }
}
