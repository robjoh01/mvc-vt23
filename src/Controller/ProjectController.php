<?php

namespace App\Controller;

use Exception;

use App\Card\Card;
use App\Game\PokerGame;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProjectController extends AbstractController
{
    #[Route("/proj", name: "project")]
    public function projectHome(
        SessionInterface $session
    ): Response {
        $isPlaying = $session->get("has_initialize", false);

        $data = [
            "isPlaying" => $isPlaying
        ];

        return $this->render('project/home.html.twig', $data);
    }

    #[Route("/proj/api", name: "project_api")]
    public function projectAPI(): Response
    {
        $data = [
            "placeCardURL" => $this->generateUrl('api_project_game_draw_card'),
        ];

        return $this->render('project/api.html.twig', $data);
    }

    #[Route("/proj/about", name: "project_about")]
    public function projectAbout(): Response
    {
        return $this->render('project/about.html.twig');
    }

    #[Route("/proj/play", name: "project_play", methods: ['POST'])]
    public function projectPlay(
        SessionInterface $session
    ): Response {
        $game = new PokerGame();

        $session->set("game", $game);
        $session->set("has_initialize", true);
        $session->set("start_date", date('Y-m-d H:i:s'));

        return $this->redirectToRoute("project_playing");
    }

    #[Route("/proj/end", name: "project_end")]
    public function projectEnd(
        SessionInterface $session
    ): Response {
        $session->set("has_initialize", false);
        $session->set("end_date", date('Y-m-d H:i:s'));

        /** @var string */
        $startDateStr = $session->get("start_date", "");

        /** @var string */
        $endDateStr = $session->get("end_date", "");

        $startDate = new \DateTime($startDateStr);
        $endDate = new \DateTime($endDateStr);

        $diffDate = $startDate->diff($endDate);
        $session->set("diff_date", $diffDate);

        /** @var PokerGame */
        $game = $session->get("game", null);

        $gameData = $game->getData();

        /** @var array<array<Card|null>> */
        $board = $gameData["board"];

        /** @var int */
        $totalPoints = $game->getTotalHandRankPoints();

        $session->set("game", $game);

        $data = [
            "game" => $game,
            "board" => $board,
            "totalPoints" => $totalPoints,
        ];

        return $this->render("project/end.html.twig", $data);
    }

    #[Route("/proj/playing", name: "project_playing")]
    public function projectPlaying(
        SessionInterface $session
    ): Response {
        $hasInitialize = $session->get("has_initialize", false);

        if (!$hasInitialize) {
            return $this->redirectToRoute("project_play");
        }

        /** @var PokerGame */
        $game = $session->get("game", null);

        if ($game->isCompleted()) {
            return $this->redirectToRoute("project_end");
        }

        $gameData = $game->getData();

        /** @var array<array<Card|null>> */
        $board = $gameData["board"];

        /** @var int */
        $totalPoints = $game->getTotalHandRankPoints();

        /** @var Card */
        $selectedCard = $gameData["selectedCard"];

        $data = [
            "game" => $game,
            "board" => $board,
            "selectedCard" => $selectedCard,
            "totalPoints" => $totalPoints,
        ];

        return $this->render("project/playing.html.twig", $data);
    }

    #[Route("/proj/add/card", name: "project_add_card", methods: ['POST'])]
    public function projectAddCard(
        Request $request,
        SessionInterface $session,
    ): Response {
        /** @var PokerGame */
        $game = $session->get("game", null);

        /** @var Card */
        $selectedCard = $game->peekSelectedCard();

        $row = ((int)$request->request->get('row', 1)) - 1;
        $column = ((int)$request->request->get('column', 1)) - 1;

        if ($row < 0 || $row > 5) {
            throw new Exception("Row cannot be above 5 or below 0!");
        }

        if ($column < 0 || $column > 5) {
            throw new Exception("Column cannot be above 5 or below 0!");
        }

        if ($game->hasBoardElement($row, $column)) {
            // TODO: Add warning text: "This position is already taken!"
            return $this->redirectToRoute("project_playing");
        }

        $game->popSelectedCard();
        $game->setBoardElement($row, $column, $selectedCard);

        $session->set("game", $game);

        return $this->redirectToRoute("project_playing");
    }
}
