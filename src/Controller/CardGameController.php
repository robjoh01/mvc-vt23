<?php

namespace App\Controller;

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
    #[Route("/card", name: "card")]
    public function cardHome(): Response
    {
        return $this->render('card/home.html.twig');
    }

    #[Route("/card/deck", name: "card_deck")]
    public function cardDeck(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfCards($session->get('deck_of_cards', null));
        $cards = $deck->getSortedCards();

        $data = [
            "cards" => $cards,
            "num_of_cards" => count($cards),
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "card_deck_shuffle", methods: ['GET'])]
    public function cardDeckShuffle(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfCards($session->get('deck_of_cards', null));
        $deck->shuffle();

        $data = [
            "cards" => $deck->getCards(),
            "num_of_cards" => $deck->getCardCount(),
        ];

        $session->set('deck_of_cards', $deck->getCards());

        return $this->render('card/shuffle.html.twig', $data);
    }

    #[Route("/card/draw", name: "card_draw")]
    public function cardDraw(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfCards($session->get('deck_of_cards', null));

        if ($deck->isEmpty()) {
            throw new \Exception("Can not draw with an empty deck.");
        }

        $hand = new CardHand();

        $hand->add($deck->draw());

        $data = [
            "cards" => $hand->getString(),
            "num_of_cards" => $hand->getCardCount(),
            "deck_num_of_cards" => $deck->getCardCount(),
        ];

        $session->set('deck_of_cards', $deck->getCards());

        return $this->render('card/draw.html.twig', $data);
    }

    #[Route("/card/draw/{num<\d+>}", name: "card_deck_number")]
    public function cardDrawNumber(
        SessionInterface $session,
        int $num
    ): Response {
        $deck = new DeckOfCards($session->get('deck_of_cards', null));

        if ($num > $deck->getCardCount()) {
            throw new \Exception("Can not draw more than '$deck->getCardCount()' cards!");
        }

        $hand = new CardHand();

        for ($i = 1; $i <= $num; $i++) {
            $hand->add($deck->draw());
        }

        $data = [
            "cards" => $hand->getString(),
            "num_of_cards" => $hand->getCardCount(),
            "deck_num_of_cards" => $deck->getCardCount(),
        ];

        $session->set('deck_of_cards', $deck->getCards());

        return $this->render('card/draw.html.twig', $data);
    }
}
