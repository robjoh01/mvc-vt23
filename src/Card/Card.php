<?php

namespace App\Card;

class Card
{
    protected string $suit;
    protected string $value;
    protected int $score;

    public function __construct(string $suit, string $value, int $score = 0)
    {
        $this->suit = $suit;
        $this->value = $value;
        $this->score = $score;
    }

    /**
     * Get custom suit (set based on deck of cards)
     */
    public function getSuit(): string
    {
        return $this->suit;
    }

    /**
     * Get custom value (set based on deck of cards)
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Get custom score value (set based on deck of cards)
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * Get value and suit together as a string.
     */
    public function getAsString(): string
    {
        return $this->value . $this->suit;
    }
}
