<?php

namespace App\Card;

class CardHand
{
    /** @var Card[] */
    private array $cards = [];

    public function add(Card $card): void
    {
        $this->cards[] = $card;
    }

    public function remove(Card $card): void
    {
        /** @var int */
        $index = array_search($card, $this->cards);

        if ($index !== false) {
            array_splice($this->cards, $index, 1);
        }
    }

    /**
     * @return array<string>
     */
    public function getString(): array
    {
        $values = [];

        foreach ($this->cards as $card) {
            $values[] = $card->getAsString();
        }

        return $values;
    }

    public function getCardCount(): int
    {
        return count($this->cards);
    }
}
