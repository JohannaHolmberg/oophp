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


    /**
     * Get the $playerHistogramString.
     *
     * @return array with the serie.
     */
    public function setplayerHistogramString($currentRollString)
    {
        var_dump($currentRollString);
        var_dump(gettype($this->playerHistogramString));

        array_push($this->playerHistogramString, $currentRollString);

        var_dump($this->playerHistogramString);

        return $this->playerHistogramString;

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
    public function printHistogram(int $min = null, int $max = null)
    {
        $number = 1; // shows which roll it was, first, second?
        $str = "";
        //$returnArray = [];

        foreach ($this->serie as $dice) {
            //ksort($value);
            //var_dump($key); // 0, 1, 2 osv
            //var_dump($value); // the values in the roll as array
            $str .= "$number: ";
            $str .= str_repeat("*", $dice);
            $str .= "<br>";
            $number += 1;



            // put this string into an array?

        }
        $str .= "- - - - -";
        //array_push($returnArray, $str);

        //$str = "";
        $number = 1;

        //var_dump($returnArray);
        //return $returnArray;
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

        var_dump($returnString);
        return $returnString;

    }


    // maybe I need to add another layer here, since
    // // i've create a multi array for serie
    // $serie = array_count_values($this->serie);
    // ksort($serie);
    // $str = "";
    // foreach ($serie as $key => $item) {
    //     $str .= "$key: ";
    //     $str .= str_repeat("*", $item);
    //     $str .= "<br>";
    // }
    // return $str;
}
