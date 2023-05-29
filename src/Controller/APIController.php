<?php

namespace App\Controller;

use Exception;

use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;

use App\Game\Game;
use App\Game\PokerGame;

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

    #[Route('/api/library/book/{isbn}', name: 'api_library_book')]
    public function getLibraryBookFromISBN(
        BookRepository $bookRepository,
        string $isbn,
    ): Response {
        $books = $bookRepository->findAll();

        $data = [];

        foreach ($books as $book) {
            if ($isbn == $book->getIsbn()) {
                $data = $bookRepository->find($book->getId());
            }
        }

        $response = $this->json($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route('/api/project/game', name: 'api_project_game')]
    public function getCurrentStatusOfProjectGame(
        SessionInterface $session
    ): Response {
        /** @var PokerGame */
        $game = $session->get("game", null);

        $gameData = $game->getData();

        /** @var string[] */
        $boardAsString = [];

        /** @var array<array<Card|null>> */
        $board = $gameData["board"];

        foreach ($board as $row) {
            foreach ($row as $card) {
                if ($card !== null) {
                    $boardAsString[] = $card->getAsString();
                } else {
                    $boardAsString[] = "NULL";
                }
            }
        }

        /** @var Card */
        $selectedCard = $gameData["selectedCard"];

        $hasInitialize = $session->get("has_initialize", false);

        $rowPoints = [];
        $columnPoints = [];

        for ($row = 0; $row < 5; $row++) {
            $rowPoints[] = $game->getPointsFromHandRank($game->getRowHandRank($row));
        }

        for ($column = 0; $column < 5; $column++) {
            $columnPoints[] = $game->getPointsFromHandRank($game->getColumnHandRank($column));
        }

        $data = [
            "has_initialize" => $hasInitialize,
            "board" => $boardAsString,
            "selected_card" => $selectedCard->getAsString(),
            "row_points" => $rowPoints,
            "column_points" => $columnPoints,
            "total_points" => $game->getTotalHandRankPoints(),
            "isCompleted" => $game->isCompleted(),
            "start_date" => $session->get("start_date"),
            "end_date" => $session->get("end_date"),
            "diff_date" => $session->get("diff_date"),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        return $response;
    }

    #[Route('/api/project/game/points', name: 'api_project_game_points')]
    public function getCurrentStatusOfProjectGamePoints(
        SessionInterface $session
    ): Response {
        /** @var PokerGame */
        $game = $session->get("game", null);

        $data = [
            "total_points_based_on_hand_rankings" => $game->getTotalHandRankPoints(),
            "total_points_based_on_cards_itself" => $game->getTotalPoints(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        return $response;
    }

    private function getTotalHours(\DateInterval $di): int
    {
        return ($di->d * 24) + $di->h + $di->i / 60;
    }

    private function getTotalMinutes(\DateInterval $di): int
    {
        return ($di->d * 24 * 60) + ($di->h * 60) + $di->i;
    }

    private function getTotalSeconds(\DateInterval $di): int
    {
        return ($di->d * 24 * 60 * 60) + ($di->h * 60 * 60) + ($di->i * 60) + $di->s;
    }

    #[Route('/api/project/game/date', name: 'api_project_game_date')]
    public function getCurrentStatusOfProjectGameDate(
        SessionInterface $session
    ): Response {
        /** @var \DateInterval */
        $diffDate = $session->get("diff_date", new \DateInterval("P0D"));

        $data = [
            "start_date" => $session->get("start_date"),
            "end_date" => $session->get("end_date"),
            "play_hours" => $this->getTotalHours($diffDate),
            "play_minutes" => $this->getTotalMinutes($diffDate),
            "play_seconds" => $this->getTotalSeconds($diffDate),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        return $response;
    }

    #[Route('/api/project/game/rows/{row}', name: 'api_project_game_row')]
    public function getCurrentPointForRow(
        SessionInterface $session,
        int $row,
    ): Response {
        /** @var PokerGame */
        $game = $session->get("game", null);

        if ($row < 0 || $row > 4) {
            throw new Exception("Row index is out of bounds!");
        }

        $handRank = $game->getRowHandRank($row);
        $points = $game->getPointsFromHandRank($handRank);

        $data = [
            "points" => $points,
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        return $response;
    }

    #[Route('/api/project/game/columns/{column}', name: 'api_project_game_column')]
    public function getCurrentPointForColumn(
        SessionInterface $session,
        int $column,
    ): Response {
        /** @var PokerGame */
        $game = $session->get("game", null);

        if ($column < 0 || $column > 4) {
            throw new Exception("Column index is out of bounds!");
        }

        $handRank = $game->getColumnHandRank($column);
        $points = $game->getPointsFromHandRank($handRank);

        $data = [
            "points" => $points,
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        return $response;
    }

    #[Route("/api/project/game/draw/", name: "api_project_game_draw_card", methods: ['POST'])]
    public function drawRandomCardFromPokerGame(
        Request $request,
        SessionInterface $session
    ): Response {
        $row = $request->request->get('row_index');
        $column = $request->request->get('column_index');

        if ($row < 0 || $row > 5) {
            throw new Exception("Row cannot be above 5 or below 0!");
        }

        if ($column < 0 || $column > 5) {
            throw new Exception("Column cannot be above 5 or below 0!");
        }

        /** @var PokerGame */
        $game = $session->get("game", null);

        $gameData = $game->getData();

        /** @var array<array<Card|null>> */
        $board = $gameData["board"];

        /** @var Card */
        $selectedCard = $game->peekSelectedCard();

        $message = "";

        if ($game->hasBoardElement($row, $column)) {
            $message = "Position is already taken! Chose another position.";
        } else {
            $game->popSelectedCard();
            $game->setBoardElement($row, $column, $selectedCard);
            $message = "Card has successfully been added!";
        }

        $data = [
            "selected_card" => $selectedCard->getAsString(),
            "message" => $message,
            "board" => [
                "rows" => [],
                "columns" => []
            ]
        ];

        // Iterate over each row in the board
        for ($row = 0; $row < count($board); $row++) {
            $rowKey = "row" . sprintf("%02d", $row + 1);
            $data["board"]["rows"][$rowKey] = [];

            // Iterate over each column in the row
            for ($column = 0; $column < count($board[$row]); $column++) {
                $columnKey = "column" . sprintf("%02d", $column + 1);
                $card = $board[$row][$column];
                $value = is_null($card) ? null : $card->getAsString();

                // Add the card value to the corresponding row and column
                $data["board"]["rows"][$rowKey][] = $value;
                $data["board"]["columns"][$columnKey][] = $value;
            }
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        return $response;
    }
}
