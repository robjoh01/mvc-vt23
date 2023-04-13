<?php

namespace App\Card;

use App\Card\CardGraphic;

class DeckOfCards
{
    private $cards = [];
    private $suits = ['♥', '♦', '♣', '♠'];
    private $values = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];

    public function __construct(array $cards = null)
    {
        $this->cards = [];

        if ($cards !== null) {
            $this->cards = $cards;
        } else {
            foreach ($this->suits as $suit) {
                foreach ($this->values as $value) {
                    $card = new CardGraphic($suit, $value);
                    $this->cards[] = $card;
                }
            }
        }
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function shuffle()
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

    public function draw(): Card
    {
        return array_shift($this->cards);
    }

    public function getSortedCards(): array
    {
        usort($this->cards, function ($a, $b) {
            if ($a->getSuit() === $b->getSuit()) {
                $valueIndex1 = array_search($a->getValue(), $this->values);
                $valueIndex2 = array_search($b->getValue(), $this->values);

                return $valueIndex1 - $valueIndex2;
            } else {
                $suitIndex1 = array_search($a->getSuit(), $this->suits);
                $suitIndex2 = array_search($b->getSuit(), $this->suits);

                return $suitIndex1 - $suitIndex2;
            }
        });

        return $this->cards;
    }

    public function getCardCount()
    {
        return count($this->cards);
    }

    public function isEmpty()
    {
        return $this->getCardCount() <= 0;
    }
}
