<?php

namespace App\Card;

use App\Card\CardGraphic;

class DeckOfCards
{
    // (Diamond, Club, Heart, Spade)
    // (Ace, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, Jack, Queen, King)

    /** @var Card[] */
    private array $cards = [];

    /** @var string[] */
    private array $suits = ['♥', '♦', '♣', '♠'];

    /** @var string[] */
    private array $values = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];

    /** @var int[] */
    private array $scores = [14, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13];

    /** @param Card[] $cards */
    public function __construct(array $cards = null)
    {
        $this->cards = [];

        if ($cards !== null) {
            $this->cards = $cards;
            return;
        }

        foreach ($this->suits as $suit) {
            foreach ($this->values as $value) {
                $score = $this->scores[array_search($value, $this->values)];
                $card = new CardGraphic($suit, $value, $score);
                $this->cards[] = $card;
            }
        }
    }

    /**
     * Get cards from the deck.
     * @return Card[]
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * Shuffle the deck in random order.
     */
    public function shuffle(): void
    {
        $this->__construct();
        shuffle($this->cards);
    }

    /**
     * Draw one card from the deck.
     */
    public function draw(): mixed
    {
        return array_shift($this->cards);
    }

    /**
     * Get sorted cards and return it as an array.
     * @return Card[]
     */
    public function getSortedCards(): array
    {
        /** @param Card $cardA */
        /** @param Card $cardB */
        usort($this->cards, function ($cardA, $cardB) {
            if ($cardA->getSuit() === $cardB->getSuit()) {
                /** @var int */
                $valueIndex1 = array_search($cardA->getValue(), $this->values);

                /** @var int */
                $valueIndex2 = array_search($cardB->getValue(), $this->values);

                return $valueIndex1 - $valueIndex2;
            }

            /** @var int */
            $suitIndex1 = array_search($cardA->getSuit(), $this->suits);

            /** @var int */
            $suitIndex2 = array_search($cardB->getSuit(), $this->suits);

            return $suitIndex1 - $suitIndex2;
        });

        return $this->cards;
    }

    /**
     * Get count of all cards in this deck.
     */
    public function getCardCount(): int
    {
        return count($this->getCards());
    }

    /**
     * Check if card count less than zero, in this deck.
     */
    public function isEmpty(): bool
    {
        return $this->getCardCount() <= 0;
    }
}
