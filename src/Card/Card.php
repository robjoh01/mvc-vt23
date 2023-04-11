<?php

namespace App\Card;

use SplEnum;

class Suit extends SplEnum
{
    const SPADES = '♠';
    const HEARTS = '♥';
    const DIAMONDS = '♦';
    const CLUBS = '♣';
}

class Value extends SplEnum
{
    const ACE = 'A';
    const TWO = '2';
    const THREE = '3';
    const FOUR = '4';
    const FIVE = '5';
    const SIX = '6';
    const SEVEN = '7';
    const EIGHT = '8';
    const NINE = '9';
    const TEN = '10';
    const JACK = 'J';
    const QUEEN = 'Q';
    const KING = 'K';
}

class Card
{
    private $suit;
    private $value;

    public function __construct(Suit $suit, Value $value)
    {
        $this->suit = $suit;
        $this->value = $value;
    }

    public function getSuit(): Suit
    {
        return $this->suit;
    }

    public function getValue(): Value
    {
        return $this->value;
    }

    public function getAsString(): string
    {
        return $this->value . $this->suit;
    }
}
