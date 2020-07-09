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
        $dhm = new DiceHistogram();

        $histogramArray = $dhm->getHistogramSerie();
        $this->assertTrue(is_array($histogramArray));
    }


    // return type is correct = string
    public function testReturnTypeprintHistogram()
    {
        $dhm = new DiceHistogram();

        $histogramArray = $dhm->printHistogram();
        $this->assertTrue(is_string($histogramArray));
    }
}
