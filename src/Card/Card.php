<?php

namespace App\Card;

class Card
{
    protected string $suit;
    protected string $value;
    protected int $score;

    public function __construct(string $suit, string $value, int $score)
    {
        $this->suit = $suit;
        $this->value = $value;
        $this->score = $score;
    }

    public function getSuit(): string
    {
        return $this->suit;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function getAsString(): string
    {
        return $this->value . $this->suit;
    }
}
