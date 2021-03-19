<?php

namespace Jo\Dice;

/**
 * A trait implementing histogram for integers.
 */
trait HistogramTrait
{
    /**
     * @var array $serie  The numbers stored in sequence.
     */
    private $serie = [];
    private $playerHistogramString = [];
    private $computerHistogramString = [];


    /**
     * Get the $playerHistogramString.
     *
     * @return array with the serie.
     */
    public function setplayerHistogramString($currentRollString)
    {
        array_push($this->playerHistogramString, $currentRollString);

        // var_dump($this->playerHistogramString);

        return $this->playerHistogramString;
    }

    /**
     * Get the $playerHistogramString.
     *
     * @return array with the serie.
     */
    public function setcomputerHistogramString($currentRollString)
    {
        array_push($this->computerHistogramString, $currentRollString);

        // var_dump($this->computerHistogramString);

        return $this->computerHistogramString;
    }
    /**
     * Get the serie.
     *
     * @return array with the serie.
     */
    public function getplayerHistogramString()
    {
        return $this->playerHistogramString;
    }


    /**
     * Get the serie.
     *
     * @return array with the serie.
     */
    public function getcomputerHistogramString()
    {
        return $this->computerHistogramString;
    }


    /**
     * Get the serie.
     *
     * @return array with the serie.
     */
    public function getHistogramSerie()
    {
        return $this->serie;
    }


    /**
     * Print out the histogram, default is to print out only the numbers
     * in the serie, but when $min and $max is set then print also empty
     * values in the serie, within the range $min, $max.
     *
     * @param int $min The lowest possible integer number.
     * @param int $max The highest possible integer number.
     *
     * @return string representing the histogram.
     */
    public function printHistogram()
    {
        $number = 1; // shows which roll it was, first, second?
        $str = "";

        foreach ($this->serie as $dice) {
            $str .= "$number: ";
            $str .= str_repeat("*", $dice);
            $str .= "<br>";
            $number += 1;
        }
        $str .= "- - - - -";
        $number = 1;
        return $str;
    }

    /**
     * Print out the histogram, default is to print out only the numbers
     * in the serie, but when $min and $max is set then print also empty
     * values in the serie, within the range $min, $max.
     *
     * @param int $min The lowest possible integer number.
     * @param int $max The highest possible integer number.
     *
     * @return string representing the histogram.
     */
    public function printHistogramC($rolled)
    {
        $number = 1; // shows which roll it was, first, second?
        $str = "";
        //$returnArray = [];

        foreach ($rolled as $dice) {
            $str .= "$number: ";
            $str .= str_repeat("*", $dice);
            $str .= "<br>";
            $number += 1;
        }
        $str .= "- - - - -";
        $number = 1;
        $returnString = $str;
        $str = "";

        //var_dump($returnString);
        return $returnString;
    }
}
