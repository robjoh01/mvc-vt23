<?php

namespace App\Dice;

class Dice
{
    protected int $value;

    public function __construct()
    {
        $this->value = -1;
    }

    /**
     * Roll a random value between 1 and 6.
     */
    public function roll(): int
    {
        $this->value = random_int(1, 6);
        return $this->value;
    }

    /**
     * Get value.
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * Get value as a string. Format: [5⚀]
     */
    public function getAsString(): string
    {
        return "[{$this->value}]";
    }
}
