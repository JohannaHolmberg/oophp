<?php

namespace Jo\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Example test class.
 */
class HandTest extends TestCase
{
    // create a hand object without problem
    public function testDiceCreation()
    {
        $hand = new Hand();
        $this->assertInstanceOf("\Jo\Dice\Hand", $hand);
    }

    // test that all values in hand are betwean 1-6
    public function testValueInHand()
    {
        $hand = new Hand();
        $handValue = $hand->getDiceValue();
        $this->assertContains($handValue[0]->getValue(), [1, 2, 3, 4, 5, 6]);
    }
}
