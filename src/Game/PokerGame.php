<?php

namespace App\Game;

use Exception;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;

use App\Poker\PokerRanker;
use App\Poker\PokerRank;

class PokerGame
{
    private DeckOfCards $deck; // The deck of cards used in the game.
    private Card $selectedCard; // The currently selected card.

    /**
     * The game board containing the cards.
     *
     * @var array<array<Card|null>>
     */
    private array $board;

    public function __construct()
    {
        $this->deck = new DeckOfCards();

        $this->deck->shuffle();

        $this->board = array(
            array(null, null, null, null, null),
            array(null, null, null, null, null),
            array(null, null, null, null, null),
            array(null, null, null, null, null),
            array(null, null, null, null, null),
        );

        /** @var Card */
        $card =  $this->deck->draw();

        $this->selectedCard = $card;
    }

   /**
     * Pop and return the currently selected card from the deck.
     *
     * @return Card The previously selected card.
     */
    public function popSelectedCard(): Card
    {
        $temp = $this->selectedCard;

        /** @var Card */
        $card =  $this->deck->draw();

        $this->selectedCard = $card;

        return $temp;
    }

    /**
     * Get the currently selected card.
     *
     * @return Card The currently selected card.
     */
    public function peekSelectedCard(): Card
    {
        return $this->selectedCard;
    }

    /**
     * Get the card at the specified position on the board.
     *
     * @param int $row The row index.
     * @param int $column The column index.
     * @return Card|null The card at the specified position, or null if empty.
     */
    public function getBoardElement(int $row, int $column): Card|null
    {
        return $this->board[$row][$column];
    }

    /**
     * Set the card at the specified position on the board.
     *
     * @param int $row The row index.
     * @param int $column The column index.
     * @param Card|null $card The card to be set, or null to clear the position.
     * @return void
     */
    public function setBoardElement(int $row, int $column, Card|null $card): void
    {
        $this->board[$row][$column] = $card;
    }

    /**
     * Check if a card exists at the specified position on the board.
     *
     * @param int $row The row index.
     * @param int $column The column index.
     * @return bool True if a card exists, false otherwise.
     */
    public function hasBoardElement(int $row, int $column): bool
    {
        return !is_null($this->getBoardElement($row, $column));
    }

    /**
     * Check if the game is completed.
     *
     * @return bool True if the game is completed, false otherwise.
     */
    public function isCompleted(): bool
    {
        $rows = count($this->board);
        $columns = count($this->board[0]);

        for ($row = 0; $row < $rows; $row++) {
            for ($column = 0; $column < $columns; $column++) {
                if (!$this->hasBoardElement($row, $column)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get the hand rank of a specific row on the board.
     *
     * @param int $row The row index.
     * @return string The hand rank of the cards in the specified row.
     */
    public function getRowHandRank(int $row): string
    {
        $hand = new CardHand();

        foreach ($this->board[$row] as $rowCard) {
            /** @var Card */
            $card = $rowCard;

            if (is_null($card)) {
                continue;
            }

            $hand->addCard($card);
        }

        return PokerRanker::getHandRank($hand);
    }

    /**
     * Get the hand rank of a specific column on the board.
     *
     * @param int $column The column index.
     * @return string The hand rank of the cards in the specified column.
     */
    public function getColumnHandRank(int $column): string
    {
        $hand = new CardHand();

        foreach ($this->board as $row) {
            /** @var Card */
            $card = $row[$column];

            if (is_null($card)) {
                continue;
            }

            $hand->addCard($card);
        }

        return PokerRanker::getHandRank($hand);
    }

    /**
     * Get the total points from all the hand ranks on the board.
     *
     * @return int The total points calculated from the hand ranks.
     */
    public function getTotalHandRankPoints(): int
    {
        $totalPoints = 0;

        // Loop through each row
        foreach ($this->board as $rowIndex => $row) {
            $rowHandRank = $this->getRowHandRank($rowIndex);
            $rowPoints = $this->getPointsFromHandRank($rowHandRank);
            $totalPoints += $rowPoints;
        }

        // Loop through each column
        $columnsCount = count($this->board[0]);

        for ($column = 0; $column < $columnsCount; $column++) {
            $columnHandRank = $this->getColumnHandRank($column);
            $columnPoints = $this->getPointsFromHandRank($columnHandRank);
            $totalPoints += $columnPoints;
        }

        return $totalPoints;
    }

    /**
     * Get the points corresponding to a specific hand rank.
     *
     * @param string $handRank The hand rank.
     * @return int The points corresponding to the hand rank.
     */
    public function getPointsFromHandRank(string $handRank): int
    {
        switch ($handRank) {
            default:
            case PokerRank::HIGH_CARD:
                return 0;

            case PokerRank::ONE_PAIR:
                return 2;

            case PokerRank::TWO_PAIR:
                return 5;

            case PokerRank::THREE_OF_A_KIND:
                return 10;

            case PokerRank::STRAIGHT:
                return 15;

            case PokerRank::FLUSH:
                return 20;

            case PokerRank::FULL_HOUSE:
                return 25;

            case PokerRank::FOUR_OF_A_KIND:
                return 50;

            case PokerRank::STRAIGHT_FLUSH:
                return 75;

            case PokerRank::ROYAL_FLUSH:
                return 100;
        }
    }

    /**
     * Get the points from a specific position on the board (row and column).
     *
     * @param int $row The row index.
     * @param int $column The column index.
     * @return mixed The points from the specified position, or 0 if the position is empty.
     */
    public function getRowAndColumnPoints(int $row, int $column): mixed
    {
        if ($this->hasBoardElement($row, $column)) {
            /** @var Card */
            $card = $this->getBoardElement($row, $column);

            return $card->getScore();
        }

        return 0;
    }

    /**
     * Get the total points from a specific row on the board.
     *
     * @param int $row The row index.
     * @return int The total points from the specified row.
     */
    public function getRowPoints(int $row): int
    {
        $points = 0;

        foreach ($this->board[$row] as $card) {
            if ($card !== null) {
                $points += $card->getScore();
            }
        }

        return $points;
    }

    /**
     * Get the total points from a specific column on the board.
     *
     * @param int $column The column index.
     * @return int The total points from the specified column.
     */
    public function getColumnPoints(int $column): int
    {
        $points = 0;

        foreach ($this->board as $row) {
            $card = $row[$column];

            if ($card !== null) {
                $points += $card->getScore();
            }
        }

        return $points;
    }

   /**
     * Get the total points from all the cards on the board.
     *
     * @return int The total points from all the cards.
     */
    public function getTotalPoints(): int
    {
        $points = 0;

        foreach ($this->board as $row) {
            foreach ($row as $card) {
                if ($card !== null) {
                    $points += $card->getScore();
                }
            }
        }

        return $points;
    }

    /**
     * Get the data of the PokerGame object.
     *
     * @return Mixed[] An array containing the board and the selected card.
     */
    public function getData(): array
    {
        return [
            "board" => $this->board,
            "selectedCard" => $this->selectedCard,
        ];
    }
}
