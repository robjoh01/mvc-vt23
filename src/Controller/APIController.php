<?php

namespace App\Controller;

use Exception;

use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;
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
        /** @var Card[] */
        $deckOfCards = $session->get('deck_of_cards', null);
        $deck = new DeckOfCards($deckOfCards);
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
        /** @var Card[] */
        $deckOfCards = $session->get('deck_of_cards', null);
        $deck = new DeckOfCards($deckOfCards);
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

        /** @var Card[] */
        $deckOfCards = $session->get('deck_of_cards', null);
        $deck = new DeckOfCards($deckOfCards);

        if ($deck->isEmpty()) {
            throw new Exception("Can not draw more cards!");
        }

        if ($numCards > $deck->getCardCount()) {
            $count = $deck->getCardCount();
            throw new Exception("Can not draw more than '$count' cards!");
        }

        $hand = new CardHand();

        for ($i = 1; $i <= $numCards; $i++) {
            /** @var Card */
            $newCard = $deck->draw();

            $hand->add($newCard);
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

    public function getDailyQuote(): string
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

    /**
     * @Route("/api/game")
     */
    public function getCurrentStatusOfCardGame(
        SessionInterface $session
    ): Response {
        /** @var Card[] */
        $deckOfCards = $session->get("deck_of_cards");

        /** @var Card[] */
        $playerCards = $session->get("player_cards");
        $playerScore = $session->get("player_score");

        /** @var Card[] */
        $aiCards =  $session->get("ai_cards");
        $aiScore =  $session->get("ai_score");

        /** @var string[] */
        $deckOfCardsAsString = [];

        foreach ($deckOfCards as $card) {
            $deckOfCardsAsString[] = $card->getAsString();
        }

        /** @var string[] */
        $playerCardsAsString = [];

        foreach ($playerCards as $card) {
            $playerCardsAsString[] = $card->getAsString();
        }

        /** @var string[] */
        $aiCardsAsString = [];

        foreach ($aiCards as $card) {
            $aiCardsAsString[] = $card->getAsString();
        }

        $data = [
            "player_cards" => $playerCardsAsString,
            "player_score" => $playerScore,
            "ai_cards" => $aiCardsAsString,
            "ai_score" => $aiScore,
            "is_player_winner" => $playerScore < 21 && $aiScore > 21,
            "is_ai_winner" => $aiScore < 21 && $playerScore > 21,
            "num_of_cards_in_deck" => count($deckOfCardsAsString),
            "deck_of_cards" => $deckOfCardsAsString,
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        return $response;
    }
}
