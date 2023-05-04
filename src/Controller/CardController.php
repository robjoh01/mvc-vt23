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

class CardController extends AbstractController
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
        /** @var Card[] */
        $deckOfCards = $session->get('deck_of_cards', null);
        $deck = new DeckOfCards($deckOfCards);
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
        /** @var Card[] */
        $deckOfCards = $session->get('deck_of_cards', null);
        $deck = new DeckOfCards($deckOfCards);
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
        /** @var Card[] */
        $deckOfCards = $session->get('deck_of_cards', null);
        $deck = new DeckOfCards($deckOfCards);

        if ($deck->isEmpty()) {
            throw new Exception("Can not draw with an empty deck.");
        }

        $hand = new CardHand();

        /** @var Card */
        $newCard = $deck->draw();

        $hand->addCard($newCard);

        $data = [
            "cards" => $hand->getAsString(),
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
        /** @var Card[] */
        $deckOfCards = $session->get('deck_of_cards', null);
        $deck = new DeckOfCards($deckOfCards);

        /** @var int */
        $numOfCards = $deck->getCardCount();

        if ($num > $numOfCards) {
            throw new Exception("Can not draw more than '$numOfCards' cards!");
        }

        $hand = new CardHand();

        for ($i = 1; $i <= $num; $i++) {
            /** @var Card */
            $newCard = $deck->draw();

            $hand->addCard($newCard);
        }

        $data = [
            "cards" => $hand->getAsString(),
            "num_of_cards" => $hand->getCardCount(),
            "deck_num_of_cards" => $deck->getCardCount(),
        ];

        $session->set('deck_of_cards', $deck->getCards());

        return $this->render('card/draw.html.twig', $data);
    }
}
