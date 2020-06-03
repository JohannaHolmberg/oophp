<?php

namespace Jo\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Example test class.
 */
class DiceTest extends TestCase
{
    // create a dice without problem
    public function testDiceCreation()
    {
        $dice = new Dice();
        $this->assertInstanceOf("\Jo\Dice\Dice", $dice);
    }

    // get value from Dice
    public function testTypeFromGetValueFromDice()
    {
        $dice = new Dice();
        $diceValue = $dice->getValue();
        $this->assertContains($diceValue, [1, 2, 3, 4, 5, 6]);
    }
}
