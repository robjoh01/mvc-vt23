<?php

namespace App\Card;

class CardGraphic extends Card
{
    public function __construct(string $suit, string $value, int $score)
    {
        parent::__construct($suit, $value, $score);
    }

    /**
     * Get image path based on suit and value variables
     */
    public function getImagePath(): string
    {
        $suits = ['♥', '♦', '♣', '♠'];
        $values = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];

        if (in_array($this->suit, $suits) && in_array($this->value, $values)) {
            $index = array_search($this->value, array_values($values)) + 1;
            return "img/cards/{$this->getSuitName()} {$index}.png";
        }

        return "null";
    }

    /**
     * Get image name based on suit and value variables
     */
    public function getImageName(): string
    {
        $suits = ['♥', '♦', '♣', '♠'];
        $values = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];

        if (in_array($this->suit, $suits) && in_array($this->value, $values)) {
            return "{$this->getSuitName()} {$this->value}";
        }

        return "null";
    }

    public function getSuitName(): string
    {
        switch ($this->suit) {
            case '♥':
                return 'Hearts';
            case '♦':
                return 'Diamond';
            case '♣':
                return 'Clubs';
            case '♠':
                return 'Spades';
            default:
                return "null";
        }
    }
}
