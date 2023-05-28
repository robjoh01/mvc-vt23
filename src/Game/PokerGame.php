<?php

namespace App\Game;

use Exception;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;

use App\Poker\PokerRanker;

class PokerGame
{
    private DeckOfCards $deck;
    private Card $selectedCard;

    /** @var array<array<Card|null>> */
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

    public function popSelectedCard(): Card
    {
        $card = $this->selectedCard;

        /** @var Card */
        $card =  $this->deck->draw();

        $this->selectedCard = $card;

        return $card;
    }

    public function peekSelectedCard(): Card
    {
        return $this->selectedCard;
        ;
    }

    public function getBoardElement(int $row, int $column): Card|null
    {
        return $this->board[$row][$column];
    }

    public function setBoardElement(int $row, int $column, Card $card): void
    {
        $this->board[$row][$column] = $card;
    }

    public function hasBoardElement(int $row, int $column): bool
    {
        return !is_null($this->getBoardElement($row, $column));
    }

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

    public function getRowAndColumnPoints(int $row, int $column): mixed
    {
        if ($this->hasBoardElement($row, $column)) {
            /** @var Card */
            $card = $this->getBoardElement($row, $column);

            return $card->getScore();
        }

        return 0;
    }

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

    /** @return Mixed[] */
    public function getData(): array
    {
        return [
            "board" => $this->board,
            "selectedCard" => $this->selectedCard,
        ];
    }
}
