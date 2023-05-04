<?php

namespace App\Dice;

use App\Dice\Dice;

class DiceHand
{
    /** @var Dice[] */
    private array $hand = [];

    /**
     * Add die to this array.
     */
    public function add(Dice $die): void
    {
        $this->hand[] = $die;
    }

    /**
     * Roll all dices in this array.
     */
    public function roll(): void
    {
        foreach ($this->hand as $die) {
            $die->roll();
        }
    }

    /**
     * Get number of dices.
     */
    public function getNumberDices(): int
    {
        return count($this->hand);
    }

    /**
     * Get sum value of all of the dices.
     * @return array<int>
     */
    public function getValues(): array
    {
        $values = [];

        foreach ($this->hand as $die) {
            $values[] = $die->getValue();
        }

        return $values;
    }

    /**
     * Get sum string of all of the dices.
     * @return array<string>
     */
    public function getAsString(): array
    {
        $values = [];

        foreach ($this->hand as $die) {
            $values[] = $die->getAsString();
        }

        return $values;
    }
}
