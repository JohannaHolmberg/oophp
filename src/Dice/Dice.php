<?php
namespace Jo\Dice;

/**
* Dice game, first to get to 100 wins.
*/

class Dice
{
    /**
     * @var int $number   The number of sides of this dicec.
     */

    //private $sides;
    private $value;

    /**
     * Constructor to initiate the object with current game settings,
     * if available. .
     *
     * @param int $sides The number of sides that this dice has
     *                    in the 100 Dice game.
     */

    public function __construct(int $value = 0)
    {
        if ($this->value === 0) {
            $this->value = rand(1, 6);
        }
        $this->value = $value;
        // $this->sides = $sides;
    }

    public function getValue() : int
    {
        if ($this->value === 0) {
            return $this->value = rand(1, 6);
        }
        return $this->value;
    }
}
