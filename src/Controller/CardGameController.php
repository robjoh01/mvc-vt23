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
    protected Game $game;

    public function __construct()
    {
        $this->game = new Game();
    }

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
        $this->game->initialize($session);

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

        return $this->render("game/render.html.twig", $this->game->render($session));
    }

    #[Route("/game/draw", name: "game_draw", methods: ['GET'])]
    public function drawCardGame(
        SessionInterface $session
    ): Response {
        $this->game->drawCard($session);

        if ($this->game->getPlayerScore($session) > 21) {
            return $this->redirectToRoute("game_end");
        }

        return $this->redirectToRoute("game_render");
    }

    #[Route("/game/skip", name: "game_skip", methods: ['GET'])]
    public function skipRoundGame(
        SessionInterface $session
    ): Response {
        return $this->redirectToRoute('game_ai');
    }

    #[Route("/game/ai", name: "game_ai")]
    public function aiDecisionGame(
        SessionInterface $session
    ): Response {
        $this->game->aiDecision($session);

        if ($this->game->getAIScore($session) > 21) {
            return $this->redirectToRoute("game_end");
        }

        return $this->redirectToRoute("game_render");
    }

    #[Route("/game/end", name: "game_end")]
    public function gameEnd(
        SessionInterface $session
    ): Response {
        return $this->render('game/end.html.twig', $this->game->end($session));
    }
}
