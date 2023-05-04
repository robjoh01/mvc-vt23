<?php

namespace App\Dice;

class DiceGraphic extends Dice
{
    /** @var string[] */
    private array $representation = [
        '⚀',
        '⚁',
        '⚂',
        '⚃',
        '⚄',
        '⚅',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get graphic as a string from an array of strings.
     */
    public function getAsString(): string
    {
        return $this->representation[$this->value - 1];
    }
}
