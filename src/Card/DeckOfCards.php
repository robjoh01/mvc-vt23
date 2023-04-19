<?php

namespace App\Card;

use App\Card\CardGraphic;

class DeckOfCards
{
    /** @var Card[] */
    private array $cards = [];

    /** @var string[] */
    private array $suits = ['♥', '♦', '♣', '♠'];

    /** @var string[] */
    private array $values = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];

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
                $card = new CardGraphic($suit, $value);
                array_push($this->cards, $card);
            }
        }
    }

    /** @return Card[] */
    public function getCards(): array
    {
        return $this->cards;
    }

    public function shuffle(): void
    {
        if ($this->isEmpty()) {
            foreach ($this->suits as $suit) {
                foreach ($this->values as $value) {
                    $card = new CardGraphic($suit, $value);
                    $this->cards[] = $card;
                }
            }
        }

        shuffle($this->cards);
    }

    public function draw(): mixed
    {
        return array_shift($this->cards);
    }

    /** @return Card[] */
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

    public function getCardCount(): int
    {
        return count($this->cards);
    }

    public function isEmpty(): bool
    {
        return $this->getCardCount() <= 0;
    }
}
