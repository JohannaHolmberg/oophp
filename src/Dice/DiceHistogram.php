<?php

namespace Jo\Dice;

/**
 * A dice which has the ability to show a histogram.
 */
class DiceHistogram extends GameRound
{
    use HistogramTrait;

    /**
     * Roll the dice, remember its value in the serie and return
     * its value.
     *
     * @return int the value of the rolled dice.
     */

     // public function setStringSerie($rollArray)
     // {
     //     return $this->serie = $rollArray;
     // }

     public function setSerie($rollArray) // works!!!!
     {
         return $this->serie = $rollArray;
     }


     public function roll($rolledHand) // works!!!!
     {

         // shows an array of each dice value in hand roll
         foreach ($rolledHand as $dice) {
             $this->serie[] = $dice->getValue();
             //var_dump($dice->getValue($this->serie));
         }
         return $this->serie;
     }

}



            //  // if sats för att se om den är tom eller om ett värde finns
            // if (!$playerAllRollHistogramArray) {
            //     // do stuf if array is empty
            //
            //     var_dump("empty");
            //
            //     $first = array( 1, 2);
            //     $playerAllRollHistogramArray = array($returnPrintHistogram);
            //
            //
            //     ///array_push($playerAllRollHistogramArray, $returnPrintHistogram);
            //     //$playerAllRollHistogramArray = $returnPrintHistogram;
            //     var_dump($playerAllRollHistogramArray);
            // } else {
            //     // do stuf if array is not empty
            //     var_dump("not empty");
            //     array_unshift($playerAllRollHistogramArray, $returnPrintHistogram);
            //
            // }









            // // if it's the first roll then
            // if ($playerAllRollHistogramArray === null) {
            //     var_dump("emtpy");
            //     $HistogramValues = $hV->roll($pHand->getDiceValue()); // get current roll
            //
            //     $returnPrintHistogram = $hV->printHistogram(1, 6); // return string with roll ****
            //
            //
            //
            //     // this should add an array to the beginning of the array
            //
            //     // this does not work the first time because $playerAllRollHistogramArray == null
            //     //array_unshift($playerAllRollHistogramArray, $returnPrintHistogram);
            //     $playerAllRollHistogramArray = $returnPrintHistogram;
            //     var_dump($playerAllRollHistogramArray); // this is still, because it onlye playec the first one
            //
            //
            //     // set the first roll string to session
            //     $session->set("playerAllRollHistogramArray", $playerAllRollHistogramArray);
            //
            //
            //
            //     //array_push($playerAllRollHistogramArray, $HistogramValues); // add current toll to total roll array
            //
            //
            //     //$playerAllRollHistogramArray = $HistogramValues; // this returns key=values  instead of an extra array with key=value
            //     // my plan with array_push was to add one array after the other.
            //     // but it seams that it does not work and that I'm replacing it.
            //     // TRY
            //     // to make a old = new, to see if I get same result
            //
            //     //$session->set("playerAllRollHistogramArray", $playerAllRollHistogramArray); // set the new total roll array to session
            //     //$hV->setSerie($playerAllRollHistogramArray); // set the this->series to the current total array
            //
            // } elseif (!$playerAllRollHistogramArray === null) {
            //     var_dump("second");
            //     $playerAllRollHistogramArray = $session->get("playerAllRollHistogramArray"); // get the last roll from session
            //
            //     $HistogramValues = $hV->roll($pHand->getDiceValue()); // get current roll
            //
            //     $returnPrintHistogram = $hV->printHistogram(1, 6); // return string with roll ****
            //
            //     // this should add an array to the beginning of the array
            //     array_unshift($playerAllRollHistogramArray, $returnPrintHistogram);
            //
            //
            //     // put the old and new roll together in array
            //     //array_push($playerAllRollHistogramArray, $HistogramValues);
            //     $session->set("playerAllRollHistogramArray", $playerAllRollHistogramArray);
            //     //var_dump($playerAllRollHistogramArray);
            //     //$hV->setSerie($playerAllRollHistogramArray);
            //     var_dump($playerAllRollHistogramArray);
            // }
