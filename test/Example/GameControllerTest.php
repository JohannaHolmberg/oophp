<?php

namespace Jo\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Example test class.
 */
class GameControllerTest extends TestCase
{
    // create a hand object without problem
    public function testgameRoundCreation()
    {
        $gameController = new GameController();
        $this->assertInstanceOf("\Jo\Dice\GameController", $gameController);
    }

    // test a hand with a 1 value to get true
    public function testIsBiggerThan100()
    {
        $gameRound = new GameController();
        $bigger = $gameRound->isBigger100(101);
        $this->assertTrue($bigger);
    }

    // test a hand without a 1 value to get false
    public function testIsSmallerThan100()
    {
        $gameRoundSmaller = new GameController();
        $smaller = $gameRoundSmaller->isBigger100(99);
        $this->assertFalse($smaller);
    }

    // test to update player round score
    public function testUpdateRoundPlayerScore()
    {
        $gameRound = new GameController();
        $updateScore = $gameRound->updateRoundPlayerScore(10);
        $this->assertEquals(10, $updateScore);
    }


    // test to update computer round score
    public function testupdateTotPlayerScore()
    {
        $gameRound = new GameController();
        $updateTotScore = $gameRound->updateTotPlayerScore(10);
        $this->assertEquals(10, $updateTotScore);
    }


    // test to set round score to 0
    public function testPlayerScoreToZero()
    {
        $gameRound = new GameController();
        $zeroScore = $gameRound->setPlayerScoreToZero();
        $this->assertEquals(0, $zeroScore);
    }

    // test that the sum is an int in sumHandValueForOneRound
    public function testSumHandValueForOneRound()
    {
        $hand = new Hand();
        $gameController = new GameController();

        $sum = $gameController->sumHandValueForOneRound($hand);
        $this->assertIsInt($sum);
    }

    // test getTotalPlayer score
    // test roundPlayerScore score
    // test totComputerScore score
    public function testgetTotPlayerScored()
    {
        $gameController = new GameController(10, 11, 12);

        $tps = $gameController->getTotPlayerScore();
        $this->assertEquals(10, $tps);

        $cpc = $gameController->getTotComputerScore();
        $this->assertEquals(11, $cpc);

        $rps = $gameController->getRoundPlayerScore();
        $this->assertEquals(12, $rps);
    }

    public function testrollComputerHasAValueOne()
    {
        $hand = new Hand();
        $gameController = new GameController();

        $hasOne = $gameController->rollComputerHasAValueOne($hand);

        $this->assertEquals(is_bool($hasOne), $hasOne);
    }

    public function testrollHasAValueOne()
    {
        $hand = new Hand();
        $gameController = new GameController();

        $hasOne = $gameController->rollHasAValueOne($hand);

        $this->assertEquals(is_bool($hasOne), $hasOne);
    }


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
