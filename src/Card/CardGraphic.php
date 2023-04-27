<?php

namespace App\Card;

class CardGraphic extends Card
{
    public function __construct(string $suit, string $value, int $score)
    {
        parent::__construct($suit, $value, $score);
    }

    public function getImagePath(): string
    {
        switch ([$this->suit, $this->value]) {
            case ["♥", "A"]:
                return "img/cards/Hearts 1.png";
            case ["♥", "2"]:
                return "img/cards/Hearts 2.png";
            case ["♥", "3"]:
                return "img/cards/Hearts 3.png";
            case ["♥", "4"]:
                return "img/cards/Hearts 4.png";
            case ["♥", "5"]:
                return "img/cards/Hearts 5.png";
            case ["♥", "6"]:
                return "img/cards/Hearts 6.png";
            case ["♥", "7"]:
                return "img/cards/Hearts 7.png";
            case ["♥", "8"]:
                return "img/cards/Hearts 8.png";
            case ["♥", "9"]:
                return "img/cards/Hearts 9.png";
            case ["♥", "10"]:
                return "img/cards/Hearts 10.png";
            case ["♥", "J"]:
                return "img/cards/Hearts 11.png";
            case ["♥", "Q"]:
                return "img/cards/Hearts 12.png";
            case ["♥", "K"]:
                return "img/cards/Hearts 13.png";

            case ["♦", "A"]:
                return "img/cards/Diamond 1.png";
            case ["♦", "2"]:
                return "img/cards/Diamond 2.png";
            case ["♦", "3"]:
                return "img/cards/Diamond 3.png";
            case ["♦", "4"]:
                return "img/cards/Diamond 4.png";
            case ["♦", "5"]:
                return "img/cards/Diamond 5.png";
            case ["♦", "6"]:
                return "img/cards/Diamond 6.png";
            case ["♦", "7"]:
                return "img/cards/Diamond 7.png";
            case ["♦", "8"]:
                return "img/cards/Diamond 8.png";
            case ["♦", "9"]:
                return "img/cards/Diamond 9.png";
            case ["♦", "10"]:
                return "img/cards/Diamond 10.png";
            case ["♦", "J"]:
                return "img/cards/Diamond 11.png";
            case ["♦", "Q"]:
                return "img/cards/Diamond 12.png";
            case ["♦", "K"]:
                return "img/cards/Diamond 13.png";

            case ["♣", "A"]:
                return "img/cards/Clubs 1.png";
            case ["♣", "2"]:
                return "img/cards/Clubs 2.png";
            case ["♣", "3"]:
                return "img/cards/Clubs 3.png";
            case ["♣", "4"]:
                return "img/cards/Clubs 4.png";
            case ["♣", "5"]:
                return "img/cards/Clubs 5.png";
            case ["♣", "6"]:
                return "img/cards/Clubs 6.png";
            case ["♣", "7"]:
                return "img/cards/Clubs 7.png";
            case ["♣", "8"]:
                return "img/cards/Clubs 8.png";
            case ["♣", "9"]:
                return "img/cards/Clubs 9.png";
            case ["♣", "10"]:
                return "img/cards/Clubs 10.png";
            case ["♣", "J"]:
                return "img/cards/Clubs 11.png";
            case ["♣", "Q"]:
                return "img/cards/Clubs 12.png";
            case ["♣", "K"]:
                return "img/cards/Clubs 13.png";

            case ["♠", "A"]:
                return "img/cards/Spades 1.png";
            case ["♠", "2"]:
                return "img/cards/Spades 2.png";
            case ["♠", "3"]:
                return "img/cards/Spades 3.png";
            case ["♠", "4"]:
                return "img/cards/Spades 4.png";
            case ["♠", "5"]:
                return "img/cards/Spades 5.png";
            case ["♠", "6"]:
                return "img/cards/Spades 6.png";
            case ["♠", "7"]:
                return "img/cards/Spades 7.png";
            case ["♠", "8"]:
                return "img/cards/Spades 8.png";
            case ["♠", "9"]:
                return "img/cards/Spades 9.png";
            case ["♠", "10"]:
                return "img/cards/Spades 10.png";
            case ["♠", "J"]:
                return "img/cards/Spades 11.png";
            case ["♠", "Q"]:
                return "img/cards/Spades 12.png";
            case ["♠", "K"]:
                return "img/cards/Spades 13.png";

            default:
                return "null";
        }
    }

    public function getImageName(): string
    {
        switch ([$this->suit, $this->value]) {
            case ["♥", "A"]:
                return "Hearts 1";
            case ["♥", "2"]:
                return "Hearts 2";
            case ["♥", "3"]:
                return "Hearts 3";
            case ["♥", "4"]:
                return "Hearts 4";
            case ["♥", "5"]:
                return "Hearts 5";
            case ["♥", "6"]:
                return "Hearts 6";
            case ["♥", "7"]:
                return "Hearts 7";
            case ["♥", "8"]:
                return "Hearts 8";
            case ["♥", "9"]:
                return "Hearts 9";
            case ["♥", "10"]:
                return "Hearts 10";
            case ["♥", "J"]:
                return "Hearts 11";
            case ["♥", "Q"]:
                return "Hearts 12";
            case ["♥", "K"]:
                return "Hearts 13";

            case ["♦", "A"]:
                return "Diamond 1";
            case ["♦", "2"]:
                return "Diamond 2";
            case ["♦", "3"]:
                return "Diamond 3";
            case ["♦", "4"]:
                return "Diamond 4";
            case ["♦", "5"]:
                return "Diamond 5";
            case ["♦", "6"]:
                return "Diamond 6";
            case ["♦", "7"]:
                return "Diamond 7";
            case ["♦", "8"]:
                return "Diamond 8";
            case ["♦", "9"]:
                return "Diamond 9";
            case ["♦", "10"]:
                return "Diamond 10";
            case ["♦", "J"]:
                return "Diamond 11";
            case ["♦", "Q"]:
                return "Diamond 12";
            case ["♦", "K"]:
                return "Diamond 13";

            case ["♣", "A"]:
                return "Clubs 1";
            case ["♣", "2"]:
                return "Clubs 2";
            case ["♣", "3"]:
                return "Clubs 3";
            case ["♣", "4"]:
                return "Clubs 4";
            case ["♣", "5"]:
                return "Clubs 5";
            case ["♣", "6"]:
                return "Clubs 6";
            case ["♣", "7"]:
                return "Clubs 7";
            case ["♣", "8"]:
                return "Clubs 8";
            case ["♣", "9"]:
                return "Clubs 9";
            case ["♣", "10"]:
                return "Clubs 10";
            case ["♣", "J"]:
                return "Clubs 11";
            case ["♣", "Q"]:
                return "Clubs 12";
            case ["♣", "K"]:
                return "Clubs 13";

            case ["♠", "A"]:
                return "Spades 1";
            case ["♠", "2"]:
                return "Spades 2";
            case ["♠", "3"]:
                return "Spades 3";
            case ["♠", "4"]:
                return "Spades 4";
            case ["♠", "5"]:
                return "Spades 5";
            case ["♠", "6"]:
                return "Spades 6";
            case ["♠", "7"]:
                return "Spades 7";
            case ["♠", "8"]:
                return "Spades 8";
            case ["♠", "9"]:
                return "Spades 9";
            case ["♠", "10"]:
                return "Spades 10";
            case ["♠", "J"]:
                return "Spades 11";
            case ["♠", "Q"]:
                return "Spades 12";
            case ["♠", "K"]:
                return "Spades 13";

            default:
                return "null";
        }
    }
}
