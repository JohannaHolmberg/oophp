<?php
namespace Jo\Dice;

/**
* Dice game, first to get to 100 wins.
*/

class GameRound
{
    private $playerHand;
    private $computerHand;

    /**
     * Constructor to initiate the object with current game settings,
     * if available. .
     *
     * @param object $playerHand     New hand with 6 dices each round
     * @param object $computerHand     New hand with 6 dices each round
     * @param int $playerRoundScore    Start score for player
     * @param int $computerRoundScore  Start score for computer
     *
     */
    public function __construct()
    {
        $this->playerHand = new Hand();
        $this->computerHand = new Hand();
    }

    public function getPlayerHand()
    {
        return $this->playerHand;
    }

    public function getComputerHand()
    {
        return $this->computerHand;
    }
}
