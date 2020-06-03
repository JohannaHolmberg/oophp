<?php

namespace Jo\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Example test class.
 */
class GameRoundTest extends TestCase
{
    // create a hand object without problem
    public function testgameRoundCreation()
    {
        $gameRound = new GameRound();
        $this->assertInstanceOf("\Jo\Dice\GameRound", $gameRound);
    }
}
