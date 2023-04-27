<?php

namespace App\Controller;

use Exception;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CardGameController extends AbstractController
{
    #[Route("/game", name: "game")]
    public function home(): Response
    {
        return $this->render("game/home.html.twig");
    }

    #[Route("/game/doc", name: "game_doc")]
    public function documentation(): Response
    {
        return $this->render("game/doc.html.twig");
    }

    #[Route("/game/init", name: "game_init")]
    public function initGame(
        SessionInterface $session
    ): Response {
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

        return $this->redirectToRoute("game_render");
    }

    #[Route("/game/render", name: "game_render")]
    public function renderGame(
        SessionInterface $session
    ): Response {
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

        return $this->render("game/render.html.twig", $data);
    }

    #[Route("/game/draw", name: "game_draw")]
    public function drawCardGame(
        SessionInterface $session
    ): Response {
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

        if ($playerScore > 21) {
            return $this->redirectToRoute("game_end");
        }

        return $this->redirectToRoute("game_render");
    }

    #[Route("/game/skip", name: "game_skip")]
    public function skipRoundGame(
        SessionInterface $session
    ): Response {
        return $this->redirectToRoute('game_ai');
    }

    #[Route("/game/ai", name: "game_ai")]
    public function aiDecisionGame(
        SessionInterface $session
    ): Response {
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

        if ($aiScore > 21) {
            return $this->redirectToRoute("game_end");
        }

        return $this->redirectToRoute("game_render");
    }

  #[Route("/game/end", name: "game_end")]
    public function gameEnd(
        SessionInterface $session
    ): Response {
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
            "is_player_winner" => $playerScore < 21 && $aiScore > 21,
            "is_ai_winner" => $aiScore < 21 && $playerScore > 21,
        ];

        return $this->render('game/end.html.twig', $data);
    }
}
