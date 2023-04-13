<?php

namespace App\Card;

class CardHand
{
    private $cards = [];

    public function add(Card $card): void
    {
        $this->cards[] = $card;
    }

    public function remove(Card $card): void
    {
        $index = array_search($card, $this->cards);

        if ($index !== false) {
            array_splice($this->cards, $index, 1);
        }
    }

    public function getString(): array
    {
        $values = [];

        foreach ($this->cards as $card) {
            $values[] = $card->getAsString();
        }

        return $values;
    }

    public function getCardCount()
    {
        return count($this->cards);
    }
}
