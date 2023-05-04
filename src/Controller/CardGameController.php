<?php

namespace App\Controller;

use Exception;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Game\Game;

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

    #[Route("/game/init", name: "game_init", methods: ['GET'])]
    public function initGame(
        SessionInterface $session
    ): Response {
        $game = new Game();

        $session->set("game", $game);
        $session->set("has_initialize", true);

        return $this->redirectToRoute("game_render");
    }

    #[Route("/game/render", name: "game_render")]
    public function renderGame(
        SessionInterface $session
    ): Response {
        $hasInitialize = $session->get("has_initialize", false);

        if (!$hasInitialize) {
            return $this->redirectToRoute("game_init");
        }

        /** @var Game */
        $game = $session->get("game", null);

        return $this->render("game/render.html.twig", $game->render());
    }

    #[Route("/game/draw", name: "game_draw", methods: ['GET'])]
    public function drawCardGame(
        SessionInterface $session
    ): Response {
        /** @var Game */
        $game = $session->get("game", null);

        $game->drawPlayerCard();

        $deck = $game->getDeck();
        $playerHand = $game->getPlayerHand();

        $session->set("deck_of_cards", $deck->getCards());
        $session->set("player_cards", $playerHand->getCards());
        $session->set("player_score", $playerHand->getScore());

        if ($playerHand->getScore() > 21) {
            return $this->redirectToRoute("game_end");
        }

        return $this->redirectToRoute("game_render");
    }

    #[Route("/game/skip", name: "game_skip", methods: ['GET'])]
    public function skipRoundGame(): Response
    {
        return $this->redirectToRoute('game_ai');
    }

    #[Route("/game/ai", name: "game_ai")]
    public function aiDecisionGame(
        SessionInterface $session
    ): Response {
        /** @var Game */
        $game = $session->get("game", null);

        $game->aiDecision();

        $deck = $game->getDeck();
        $aiHand = $game->getAIHand();

        $session->set("deck_of_cards", $deck->getCards());
        $session->set("ai_cards", $aiHand->getCards());
        $session->set("ai_score", $aiHand->getScore());

        if ($aiHand->getScore() > 21) {
            return $this->redirectToRoute("game_end");
        }

        return $this->redirectToRoute("game_render");
    }

    #[Route("/game/end", name: "game_end")]
    public function gameEnd(
        SessionInterface $session
    ): Response {
        /** @var Game */
        $game = $session->get("game", null);

        $session->set("has_initialize", false);

        return $this->render('game/end.html.twig', $game->end());
    }
}
