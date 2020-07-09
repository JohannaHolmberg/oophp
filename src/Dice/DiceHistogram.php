<?php

namespace Jo\Dice;

/**
 * A dice which has the ability to show a histogram.
 */
class DiceHistogram extends GameRound
{
    use HistogramTrait;

    /**
     * Roll the dice, remember its value in the serie and return
     * its value.
     *
     * @return int the value of the rolled dice.
     */

    public function setSerie($rollArray) // works!!!!
    {
        return $this->serie = $rollArray;
    }


    public function roll($rolledHand) // works!!!!
    {

        unset($this->serie); // $foo is gone
        $this->serie = array(); // $foo is here again

        // shows an array of each dice value in hand roll
        foreach ($rolledHand as $dice) {
             $this->serie[] = $dice->getValue();
             //var_dump($this->serie);
        }
        return $this->serie;
    }
}
