<?php

namespace App\Card;

use App\Card\Card;

class DeckOfCards
{
    protected $value;

    public function __construct()
    {
        $this->value = null;
    }

    public function roll(): int
    {
        $this->value = random_int(1, 6);
        return $this->value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getAsString(): string
    {
        return "[{$this->value}]";
    }
}
