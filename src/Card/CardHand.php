<?php

namespace App\Card;

class CardHand
{
    /** @var Card[] */
    private array $cards = [];

    /** @param Card[] $cards */
    public function __construct(array $cards = null)
    {
        $this->cards = [];

        if ($cards !== null) {
            $this->cards = $cards;
            return;
        }
    }

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

    /**
     * @return Card[]
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    public function getCardCount(): int
    {
        return count($this->cards);
    }

    public function getScore(): int
    {
        $score = 0;

        foreach ($this->cards as $card) {
            $score += $card->getScore();
        }

        return $score;
    }

    public function clear(): void
    {
        $this->cards = [];
    }
}
