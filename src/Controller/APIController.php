<?php

namespace App\Controller;

use Exception;

use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Game\Game;

use App\Repository\BookRepository;
use App\Entity\Book;

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

            $hand->addCard($newCard);
        }

        $data = [
            "cards" => $hand->getAsString(),
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
        /** @var ?Game */
        $game = $session->get("game", null);

        if (is_null($game)) {
            $data = [
                "has_initialize" => false,
                "player_cards" => [],
                "player_score" => 0,
                "ai_cards" => [],
                "ai_score" => 0,
                "is_player_winner" => false,
                "is_ai_winner" => false,
                "num_of_cards_in_deck" => 0,
                "deck_of_cards" => [],
            ];

            $response = new JsonResponse($data);
            $response->setEncodingOptions(
                $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            );

            return $response;
        }

        $deck = $game->getDeck();
        $playerHand = $game->getPlayerHand();
        $aiHand = $game->getAIHand();

        /** @var string[] */
        $deckOfCardsAsString = [];

        foreach ($deck->getCards() as $card) {
            $deckOfCardsAsString[] = $card->getAsString();
        }

        /** @var string[] */
        $playerCardsAsString = [];

        foreach ($playerHand->getCards() as $card) {
            $playerCardsAsString[] = $card->getAsString();
        }

        /** @var string[] */
        $aiCardsAsString = [];

        foreach ($aiHand->getCards() as $card) {
            $aiCardsAsString[] = $card->getAsString();
        }

        $hasInitialize = $session->get("has_initialize", false);

        $data = [
            "has_initialize" => $hasInitialize,
            "player_cards" => $playerCardsAsString,
            "player_score" => $playerHand->getScore(),
            "ai_cards" => $aiCardsAsString,
            "ai_score" => $aiHand->getScore(),
            "is_player_winner" => $playerHand->getScore() <= 21 && $aiHand->getScore() > 21,
            "is_ai_winner" => $aiHand->getScore() <= 21 && $playerHand->getScore() > 21,
            "num_of_cards_in_deck" => $deck->getCardCount(),
            "deck_of_cards" => $deckOfCardsAsString,
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        return $response;
    }

    #[Route('/api/library/books', name: 'api_library_books')]
    public function getLibraryBooks(
        BookRepository $bookRepository
    ): Response {
        $data = $bookRepository
            ->findAll();

        $response = $this->json($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route('api/library/book/{isbn}', name: 'api_library_book')]
    public function getLibraryBookFromISBN(
        BookRepository $bookRepository,
        string $isbn,
    ): Response {
        $books = $bookRepository->findAll();

        $data = [];
        $bookId = "";

        foreach ($books as $book) {
            if ($isbn == $book->getIsbn()) {
                $bookId = $book->getId();
                $data = $bookRepository->find($bookId);
            }
        }

        $response = $this->json($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
