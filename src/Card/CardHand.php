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
            foreach ($cards as $card) {
                $this->addCard($card);
            }
        }
    }

    /**
     * Add a card to the list
     */
    public function addCard(Card $card): void
    {
        $this->cards[] = $card;
        //array_push($this->cards, $card);
    }

    /**
     * Pop the element off the end of array.
     */
    public function popCard(): void
    {
        array_pop($this->cards);
    }

    /**
     * Remove a card from the list
     */
    public function removeCard(Card $card): void
    {
        /** @var int|string|false */
        $index = array_search($card, $this->cards);

        if ($index !== false && is_int($index)) {
            array_splice($this->cards, $index, 1);
        }
    }

    /**
     * Combine cards into a string array, using getAsString() function.
     * @return array<string>
     */
    public function getAsString(): array
    {
        $values = [];

        foreach ($this->cards as $card) {
            $values[] = $card->getAsString();
        }

        return $values;
    }

    /**
     * Get all cards from the hand.
     * @return Card[]
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * Get count of all cards in the hand.
     */
    public function getCardCount(): int
    {
        return count($this->getCards());
    }

    /**
     * Get current score of all cards combined in the hand.
     */
    public function getScore(): int
    {
        $score = 0;

        foreach ($this->cards as $card) {
            $score += $card->getScore();
        }

        return $score;
    }

    /**
     * Clear all cards from the hand.
     */
    public function clear(): void
    {
        $this->cards = [];
    }
}
