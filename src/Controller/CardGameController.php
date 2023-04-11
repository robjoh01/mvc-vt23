<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CardGameController extends AbstractController
{
    #[Route("/card", name: "card")]
    public function cardHome(): Response
    {
        return $this->render('card/home.html.twig');
    }

    #[Route("/card/deck", name: "card_deck")]
    public function cardDeck(): Response
    {
        return $this->render('card/home.html.twig');
    }

    #[Route("/card/deck/shuffle", name: "card_deck_shuffle")]
    public function cardDeckShuffle(): Response
    {
        return $this->render('card/home.html.twig');
    }

    #[Route("/card/draw", name: "card_draw")]
    public function cardDraw(): Response
    {
        return $this->render('card/home.html.twig');
    }

    #[Route("/card/deck/:number", name: "card_deck_number")]
    public function cardDeckNumber(): Response
    {
        return $this->render('card/home.html.twig');
    }
}
