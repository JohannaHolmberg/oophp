<?php

namespace Jo\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Example test class.
 */
class GameControllerTest extends TestCase
{
    public function testgetRoundComputerScore()
    {
        $hand = new Hand();
        $gameController = new GameController();

        $hasOne = $gameController->getRoundComputerScore($hand);

        $this->assertEquals(is_int($hasOne), $hasOne);
    }

    public function testsetComputerScoreToZero()
    {
        $gameController = new GameController();

        $zero = $gameController->setComputerScoreToZero();

        $this->assertEquals(0, $zero);
    }


    public function testupdateRoundComputerScore()
    {
        $gameController = new GameController();

        $zero = $gameController->updateRoundComputerScore(5);

        $this->assertEquals(5, $zero);
    }


    public function testupdateTotComputerScore()
    {
        $gameController = new GameController();

        $zero = $gameController->updateTotComputerScore(5);

        $this->assertEquals(5, $zero);
    }
}
