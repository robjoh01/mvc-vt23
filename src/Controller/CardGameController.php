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
        return $this->render('game/home.html.twig');
    }

    #[Route("/game/doc", name: "game_doc")]
    public function documentation(): Response
    {
        return $this->render('game/doc.html.twig');
    }

    #[Route("/game/init", name: "game_init")]
    public function asd(): Response
    {
        return $this->render('game/doc.html.twig');
    }
}
