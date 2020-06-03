<?php
namespace Jo\Dice;

class Hand
{
    private $dices = [];

    public function __construct()
    {
        $this->dices[0] = new Dice();
        $this->dices[1] = new Dice();
        // $this->dices[2] = new Dice();
        // $this->dices[3] = new Dice();
        // $this->dices[4] = new Dice();
    }

    public function getDiceValue()
    {
        $this->dices[0]->getValue();
        $this->dices[1]->getValue();
        // $this->dices[2]->getValue();
        // $this->dices[3]->getValue();
        // $this->dices[4]->getValue();

        return $this->dices;
    }
}
