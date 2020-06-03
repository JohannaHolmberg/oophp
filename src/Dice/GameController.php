<?php
namespace Jo\Dice;

/**
* Dice game, first to get to 100 wins.
*/

class GameController extends GameRound
{

    private $totPlayerScore;
    private $totComputerScore;
    private $roundPlayerScore;
    private $roundComputerScore;
    private $sum;

    /**
     * Constructor to initiate the object with current game settings,
     * if available. .
     *
     * @param int $totPlayerScore    Start score for player
     * @param int $totComputerScore  Start score for computer
     *
     */
    public function __construct(int $totPlayerScore = 0, int $totComputerScore = 0, int $roundPlayerScore = 0)
    {
        $this->totPlayerScore = $totPlayerScore;
        $this->roundPlayerScore = $roundPlayerScore;
        $this->totComputerScore = $totComputerScore;
    }




    /**
    *  Sums the values of each side of the dice
    *  for each roll.
    */
    public function sumHandValueForOneRound($rolledHand)
    {
        $sum = 0;
        // add the values together to get the sum of the hand
        foreach ($rolledHand as $dice) {
            $sum += $dice->getValue();
        }
        return $sum;
    }

    /**
    *  run through hand and see if one of the
    *  dices have the value 1.
    */
    public function rollHasAValueOne($rolledHand)
    {
        foreach ($rolledHand as $dice) {
            if ($dice->getValue() === 1) {
                echo "true";
                return true;
            }
        }
    }

    // if the total score is => 100 -- STILL NEED TO TRY!
    public function isBigger100($totScore)
    {
        if ($totScore >= 100) {
            return true;
        } else {
            return false;
        }
    }



    public function getTotPlayerScore()
    {
        return $this->totPlayerScore;
    }

    public function getTotComputerScore()
    {
        return $this->totComputerScore;
    }

    public function getRoundPlayerScore()
    {
        return $this->roundPlayerScore;
    }

    public function getRoundComputerScore()
    {
        return $this->computerRoundScore;
    }

    /**
    *  Sets round score to 0, when they rolled a dice with walue 1
    */
    public function setPlayerScoreToZero()
    {
        return $this->roundPlayerScore = 0;
    }



    /**
    *  Updates the TOTAL GAME SCORE - for player
    */
    public function updateRoundPlayerScore($newPoints)
    {
        $updatedPoints = $this->roundPlayerScore + $newPoints;

        $this->roundPlayerScore = $updatedPoints;

        return $this->roundPlayerScore;
    }




    /**
    *  Updates the TOTAL GAME SCORE - for player
    */
    public function updateTotPlayerScore($roundScore)
    {
        $updatedPoints = $this->totPlayerScore + $roundScore;

        $this->totPlayerScore = $updatedPoints;

        return $this->totPlayerScore;
    }


    /**
    *  Updates the TOTAL GAME SCORE - for Computer
    */


    /**
    *  Updates the TOTAL GAME SCORE - for computer
    */

}
