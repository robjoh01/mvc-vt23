<?php

namespace App\Controller;

use App\Card\DeckOfCards;
use App\Card\Card;
use App\Card\CardHand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class APIController extends AbstractController
{
    #[Route("/api", name: "api")]
    public function apiHome(): Response
    {
        $data = [
            "deckSortedURL" => $this->generateUrl('api_deck'),
            "deckShuffledURL" => $this->generateUrl('api_deck_shuffle'),
            "deckDrawURL" => $this->generateUrl('api_deck_draw'),
        ];

        return $this->render('api.html.twig', $data);
    }

    #[Route("/api/deck", name: "api_deck", methods: ['GET'])]
    public function apiDeck(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfCards($session->get('deck_of_cards', null));
        $cards = $deck->getSortedCards();

        $cardStrings = [];
        foreach ($cards as $card) {
            $cardStrings[] = $card->getAsString();
        }

        $data = [
            "cards" => $cardStrings,
            "num_of_cards" => count($cards),
        ];

        $session->set('deck_of_cards', $deck->getCards());

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        return $response;
    }

    #[Route("/api/deck/shuffle", name: "api_deck_shuffle", methods: ['POST'])]
    public function apiDeckShuffle(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfCards($session->get('deck_of_cards', null));
        $deck->shuffle();

        $cards = $deck->getCards();
        $cardStrings = [];

        foreach ($cards as $card) {
            $cardStrings[] = $card->getAsString();
        }

        $data = [
            "cards" => $cardStrings,
            "num_of_cards" => count($cards),
        ];

        $session->set('deck_of_cards', $deck->getCards());

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        return $response;
    }

    #[Route("/api/deck/draw", name: "api_deck_draw", methods: ['POST'])]
    public function apiDeckDraw(
        Request $request,
        SessionInterface $session
    ): Response {
        $numCards = $request->request->get('num_of_cards');

        $deck = new DeckOfCards($session->get('deck_of_cards', null));

        if ($deck->isEmpty()) {
            throw new \Exception("Can not draw more cards!");
        }

        if ($numCards > $deck->getCardCount()) {
            $count = $deck->getCardCount();
            throw new \Exception("Can not draw more than '$count' cards!");
        }

        $hand = new CardHand();

        for ($i = 1; $i <= $numCards; $i++) {
            $hand->add($deck->draw());
        }

        $data = [
            "cards" => $hand->getString(),
            "num_of_cards" => $hand->getCardCount(),
            "deck_num_of_cards" => $deck->getCardCount(),
        ];

        $session->set('deck_of_cards', $deck->getCards());

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        return $response;
    }

    public function getDailyQuote()
    {
        // array of quotes to select between
        $quotes = array(
            "The best way to predict the future is to invent it.",
            "In the end, we will remember not the words of our enemies, but the silence of our friends.",
            "The greatest glory in living lies not in never falling, but in rising every time we fall."
        );

        // generate a random index for the quote array
        $index = rand(0, count($quotes) - 1);

        // return the selected quote
        return $quotes[$index];
    }

    /**
     * @Route("/api/quote")
     */
    public function quote(): Response
    {
        $quote = $this->getDailyQuote();

        $data = [
            'date' => date('Y-m-d'),
            'quote' => $quote,
            'timestamp' => date('H:i:s', time())
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
