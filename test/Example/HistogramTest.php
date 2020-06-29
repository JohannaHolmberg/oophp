<?php

namespace Jo\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Example test class.
 */
class HistogramTest extends TestCase
{
    // return type is correct = array
    public function testReturnTypegetHistogramSerie()
    {
        $Dh = new DiceHistogram();

        $histogramArray = $Dh->getHistogramSerie();
        $this->assertTrue(is_array($histogramArray));
    }


    // return type is correct = string
    public function testReturnTypeprintHistogram()
    {
        $Dh = new DiceHistogram();

        $histogramArray = $Dh->printHistogram();
        $this->assertTrue(is_string($histogramArray));
    }
}
