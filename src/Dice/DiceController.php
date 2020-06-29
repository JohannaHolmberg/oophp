<?php

namespace Jo\Dice;

use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 * The controller will be injected with $app if implementing the interface
 * AppInjectableInterface, like this sample class does.
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class DiceController implements AppInjectableInterface
{
    use AppInjectableTrait;



    /**
     * @var string $db a sample member variable that gets initialised
     */
    //private $db = "not active";



    /**
     * The initialize method is optional and will always be called before the
     * target method/action. This is a convienient method where you could
     * setup internal properties that are commonly used by several methods.
     *
     * @return void
     */
    // public function initialize() : void
    // {
    //     // Use to initialise member variables.
    //     $this->db = "active";
    //
    //     // Use $this->app to access the framework services.
    // }

    /**
     * This is the index method action, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function indexAction() : string
    {
        // Deal with the action and return a response.
        return "INDEX!";
    }



    /**
     * This is the index method action, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function initAction() : object
    {
        $session = $this->app->session;

        // start the game with the GameController
        $game = new GameController();

        $session->set("totPlayerScore", $game->getTotPlayerScore());
        $session->set("totComputerScore", $game->getTotComputerScore());

        $round = new GameRound(); // create a round on init.

        $session->set("playerRoundScore", $game->getRoundPlayerScore());
        $session->set("playerHand", $round->getPlayerHand());

        $session->set("game", $game); // save game in session




        // set up for player histogram - for player
        // these needs to be here for other it will not recognize the variable to send to play
        //$playerAllRollHistogramArray = [];
        //$session->set("playerAllRollHistogramArray", $playerAllRollHistogramArray); // init on player Histogram array

        $session->set("computerHasPlayed", false);

        //remove COMPUTER histogram
        $computerAllRollHistogramArray = "";
        $session->set("computerAllRollHistogramArray", $computerAllRollHistogramArray);

        $allComputerHistogramValues = [];
        $session->set("allComputerHistogramValues", $allComputerHistogramValues);

        return $this->app->response->redirect("dice1/play");
    }



    /**
     * This is the index method action, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function playActionGet() : object
    {
        $session = $this->app->session;
        $title = "1 Dice 100 game";

        $game = $session->get("game"); // put gavecontroller in a variable
        $pHand = $session->get("playerHand"); // get value of each rolled hand

        // when changed to true, it will mean that the save button has been clicked
        // send this variable to play.php
        $savedRound = false;

        // Before rolled dice, they hand is not shown on screen
        // It only show after roll button is clicked and it changes to TRUE
        $rolledDice = false;
        $computerHasPlayed = false;



        //$playerWon = $_SESSION["playerWon"];
        $playerWon = false; // player wins when this is changed to true
        $computerWon = false; // computer wins when this is changed to true

        $computerRollHasA1Value = false;
        $playerRollHasA1Value = false;
        //$playerAllRollHistogramArray = "";

        $totComputerScore =  $session->get("totComputerScore");
        $playerRollHasA1Value =  $session->get("playerRollHasA1Value");
        $computerRollHasA1Value =  $session->get("computerRollHasA1Value");
        $printHistogramValue = $session->get("printHistogramValue");


        // PLAYER
        // create a new DiceHistogram object from class
        // to use to show histogram in game
        $hV = new DiceHistogram();

         $playerAllRollHistogramArray = array(); // create an array to contain all rolled hands
         // $playerAllRollHistogramArray = "";
        /**
        *
        * if the button "ROLL" for PLAYER has been clicked, this happens:
        *
        */
        if ($session->get("roll")) {
            /**
             * ---- START player histogram -----------------------------------
             */

             //$session->set("playerAllRollHistogramArray", $hV->getplayerHistogramString());
             $playerOld = $hV->getplayerHistogramString(); //$session->get("playerAllRollHistogramArray");

             $HistogramValues = $hV->roll($pHand->getDiceValue()); // get current roll

             $returnPrintHistogram = $hV->printHistogram(1, 6); // return string with roll ****


             $playerAllRollHistogramArray = $hV->setplayerHistogramString($returnPrintHistogram); //$playerOld . $returnPrintHistogram;

            // $playerAllRollHistogramArray = $hV->setplayerHistogramString($returnPrintHistogram);
             $session->set("playerAllRollHistogramArray", $playerAllRollHistogramArray);

            // set works, saves value
            // var_dump($session->set("playerAllRollHistogramArray", $playerAllRollHistogramArray));
            // var_dump($session->get("playerAllRollHistogramArray"));
            // ---- END player histogram -----------------------------------------



            // //remove Computer histogram
            // $computerAllRollHistogramArray = "";
            // $session->set("computerAllRollHistogramArray", $computerAllRollHistogramArray);

            // remove computer roll text
            $computerRollHasA1Value = false;
            $session->set("computerRollHasA1Value", $computerRollHasA1Value);

            // The PLAYER ROLL button has been clicked:
            $rolledDice = true; // true to send to play.php, to say dice has rolled
            // remove roll after rolled dice, otherwise the hand will always show
            $session->set("roll", null);

            // check if there is a 1, if 1 then return TRUE
            $playerRollHasA1Value = $game->rollHasAValueOne($pHand->getDiceValue());
            $session->set("playerRollHasA1Value", $playerRollHasA1Value);

            /**
             * IF STATMENT for ROLL, to see if there has been rolled a 1!
             */
            if ($playerRollHasA1Value) { // if true -> there is a 1 in the hand of dices
                // remove all points from player, set round player score to 0
                $game->setPlayerScoreToZero();
            } else { // No 1 in hand of dice, player won one round
                // get the new round hand value sum score
                $prs = $game->sumHandValueForOneRound($pHand->getDiceValue());

                // update new with old score
                $playerRoundScore = $game->updateRoundPlayerScore($prs);
            }





            // check here if tot points is more than 100
            $roundScore = $game->getRoundPlayerScore();
            $totScore = $game->getTotPlayerScore();

            if ($game->isBigger100($roundScore + $totScore)) { // create variables here to send to play.php
                $playerWon = true;
                $session->set("playerWon", $playerWon);
            }



        } // end PLAYER "roll"










        // zero out computer rolls values
        $totComputerScore = $game->getTotComputerScore();

        if ($game->isBigger100($totComputerScore)) { // check here if tot points is more than 100
            $session->set("computerWon", true);

            $computerWon = $session->get("computerWon");

        }

        // this is the rolls for each round,
        // if I save them in a new variable, that is initiated in init,
        // then I can send that variable to play,
        // to get all of the round for each game (Game Round)

        //$playerAllRollHistogramArray = $session->get("playerAllRollHistogramArray");

        //var_dump(gettype($playerAllRollHistogramArray));
        //var_dump($playerAllRollHistogramArray);
        //$computerAllRollHistogramArray = $session->get("computerAllRollHistogramArray");

        //var_dump($computerAllRollHistogramArray);
        //
        // // this will update the game variable with the round variable
        // $allComputerHistogramValues =  $session->get("allComputerHistogramValues");
        // array_push($allComputerHistogramValues, $computerAllRollHistogramArray);
        // $session->set("allComputerHistogramValues", $allComputerHistogramValues);
        //
        //
        // var_dump($allComputerHistogramValues);



        // the computer array works now and saves the strings to arrays.
        // But there is anohter proiblem, it does not print pout on play

// ------------------------------------------------------------------------------------
        //array_push($playerAllRollHistogramArray, $session->get("playerAllRollHistogramArray"));

        //$playerAllRollHistogramArray = $session->get("playerAllRollHistogramArray"); // get last roll from session
        // var_dump($playerAllRollHistogramArray);
        // $returnPrintHistogram = $hV->printHistogram(1, 6); // return string with roll ****


        // array_push($playerAllRollHistogramArray, $returnPrintHistogram);
        // var_dump($playerAllRollHistogramArray);
        //save $returnPrintHistogram somwhow. To use next time.

        // $session->set("playerAllRollHistogramArray", $playerAllRollHistogramArray);

        // var_dump($session->get("playerAllRollHistogramArray"));


// ------------------------------------------------------------------------------------






        $computerHasPlayed = $session->get("computerHasPlayed");

        $data = [
            "playerHand" => $pHand->getDiceValue() ?? "",
            "rolledDice" => $rolledDice,
            "savedRound" => $session->get("savedRound"),
            "playerRoundScore" => $game->getRoundPlayerScore(),
            "totPlayerScore" => $game->getTotPlayerScore(),
            "totComputerScore" => $game->getTotComputerScore(),
            "playerRollHasA1Value" => $playerRollHasA1Value,
            "computerRolledOne" => $computerRollHasA1Value,
            "playerWon" => $playerWon,
            "computerWon" => $computerWon,
            "playerAllRollHistogramArray" => $playerAllRollHistogramArray,//$hV->printHistogram(1, 6),
            "printComputerHistogramValue" => $computerAllRollHistogramArray ?? "", //$allComputerHistogramValues,
            "computerHasPlayed" => $computerHasPlayed
        ];

        $this->app->page->add("dice1/play", $data);

        return $this->app->page->render(["title" => $title,]);
    }



        /**
         * This is the index method action, it handles:
         * ANY METHOD mountpoint
         * ANY METHOD mountpoint/
         * ANY METHOD mountpoint/index
         *
         * @return string
         */
        public function playActionPost()
        {
            $title = "Play the game";

            $request = $this->app->request;
            $session = $this->app->session;

            // Deal with incoming variables
            $roll = $request->getPost("roll");
            $save = $request->getPost("save");
            $simulateComputer = $request->getPost("simulateComputer");



            /*****
            ****
            **  When COMPUTER simulation is clicked
            ****
            *****/

            $computerAllRollHistogramArray = array(); // create an array to contain all rolled hands
            if ($request->getPost("simulateComputer")) {

                $cH = new DiceHistogram();

                // SIMULATE COMPUTER BUTTON has been clicked:
                // change  $computerHasPlayed to true
                $computerHasPlayed = true;
                $session->set("computerHasPlayed", $computerHasPlayed);

                $session->set("simulateComputer", $simulateComputer);

                $session->set("simulateComputer", null);

                //$this->app->session->set("computerHasPlayed", null);

                $playerRollHasA1Value = false; // default value, turn to true when a roll has a value 1
                $session->set("playerRollHasA1Value", $playerRollHasA1Value); // remove player text

                $round = new GameRound(); // start a new round with the GameRound

                $game = $_SESSION["game"]; // save game object in variable to use later
                $cHand = $round->getComputerHand(); // save computer hand rolled
                $session->set("cHand", $cHand); // set the value of current roll to a session



                // ---- START COMPUTER histogram -----------------------------------------
                // set 1st (first) roll to HISTOGRAM, even if a one is in roll
                // roll() sets serie to the values in roll

                // ROLL 1 !!!!!!!!!
                $computerHistogramValues1 = $cH->roll($cHand->getDiceValue()); // get current roll
                print_r($computerHistogramValues1);


                // sätt resultatet av printHistogram i en array,
                // efter alla slag så skicka array med session.

                // this will return the string containen the roll.
                $computerRollReturnString1 = $cH->printHistogramC($computerHistogramValues1);

                // OLD _ array_push($computerAllRollHistogramArray, $computerHistogramValues);

                array_push($computerAllRollHistogramArray, $computerRollReturnString1);

                $computerRollReturnString1 = ""; // emtpy this variable for next round

                // $session->set("computerAllRollHistogramArray", $computerAllRollHistogramArray);
                // $cH->setSerie($computerAllRollHistogramArray);



                /**
                 * IF STATMENT for COMPUTER ROLL, to see if there has been rolled a 1!
                 */
                if ($game->rollComputerHasAValueOne($cHand->getDiceValue())) {  // if true -> there is a 1 in the hand of dices
                    // remove all points from player, set round player score to 0
                    $game->setComputerScoreToZero();

                    $computerRollHasA1Value = $game->rollComputerHasAValueOne($cHand->getDiceValue());

                    $session->set("computerRollHasA1Value", $computerRollHasA1Value);


                } else {  // No 1 in hand of dice,
                    // get the new round hand value sum score
                    $prs = $game->sumHandValueForOneRound($cHand->getDiceValue());
                    $computerRoundScore = $game->updateRoundComputerScore($prs); // update new with old score




                    // use the % to make computer more aggressive the higher the points are.
                    $totCScore = $game->getTotComputerScore();
                    $ran;
                    if ($totCScore >= 0) {
                        $ran = rand(1, 3); // 33% chance computer rolls again
                    } elseif ($totCScore > 50) {
                        $ran = rand(1, 2); // 50% chance computer rolls again
                    }

                    //$ran = rand(1, 2); // get a random number to see if computer wants to roll again or save

                if ($ran === 1) { // computer wants to roll again
                        $round = new GameRound();

                        // 2nd roll, even if a one is here, it should show histogram
                        //$computerAllRollHistogramArray = $session->get("computerAllRollHistogramArray"); // get the last roll from session

                        //$computerAllRollHistogramArray = $cH->getHistogramSerie();



                        $computerHistogramValues = $cH->roll($cHand->getDiceValue()); // get current roll
                        // put the old and new roll together in array

                        // this will return the string containen the roll.
                        $computerRollReturnString = $cH->printHistogramC($computerHistogramValues);

                        array_push($computerAllRollHistogramArray, $computerRollReturnString);
                        $computerRollReturnString = "";
                        //$session->set("computerAllRollHistogramArray", $computerAllRollHistogramArray);
                        //$cH->setSerie($computerAllRollHistogramArray);

                    if ($game->rollComputerHasAValueOne($cHand->getDiceValue())) {  // if true -> there is a 1 in the hand of dices
                            // remove all points from player, set round player score to 0
                            $game->setComputerScoreToZero();

                            $computerRollHasA1Value = $game->rollComputerHasAValueOne($cHand->getDiceValue());
                            //$_SESSION["computerRollHasA1Value"] = $computerRollHasA1Value;
                            $session->set("computerRollHasA1Value", $computerRollHasA1Value);
                    } else {
                            // get the new round hand value sum score
                            $prs = $game->sumHandValueForOneRound($cHand->getDiceValue());
                            $computerRoundScore = $game->updateRoundComputerScore($prs); // update new with old score

                            $totComputerScore = $game->updateTotComputerScore($computerRoundScore); // update totComputerScore
                            $computerRoundScore = $game->setComputerScoreToZero(); //set round score to 0
                    }
                } elseif ($ran === 2) {  // computer wants to save the first roll
                        $totComputerScore = $game->updateTotComputerScore($computerRoundScore); // update totComputerScore
                        $computerRoundScore = $game->setComputerScoreToZero(); //set round score to 0
                }
                }

                // set the array with the rolled strings to a session
                $session->set("computerAllRollHistogramArray", $computerAllRollHistogramArray);

                $session->set("cH", $cH);

            } // end "computer roll"

            /**
            *
            *  When PLAYER ROLL button is clicked
            *
            */
            if ($request->getPost("roll")) {
                $session->set("computerAllRollHistogramArray", "");
                $session->set("playerAllRollHistogramArray", null);

                // start a new round with the GameRound
                // Contains counts for both players and
                // new hands/rolls for both players
                $round = new GameRound();
                //$_SESSION["printHistogramValue"] = $printHistogramValue;

                $game = $session->get("game");

                $session->set("playerRoundScore", $game->getRoundPlayerScore());
                $session->set("playerHand", $round->getPlayerHand());

                $session->set("round", $round); // save round in session
                $session->set("roll", $roll);

            }


            /**
            *
            *  When SAVE button is clicked
            *
            */
            if ($request->getPost("save")) {
                $game = $session->get("game"); // get game object from session

                $session->set("playerAllRollHistogramArray", null);

                $session->set("savedRound", true);//save in session, to send to play.php;

                // get RoundScore for both players, to update to tot score
                $playerRoundScore = $game->getRoundPlayerScore();

                // update totPlayerScore
                $totPlayerScore = $game->updateTotPlayerScore($playerRoundScore);

                //set round score to 0
                $playerRoundScore = $game->setPlayerScoreToZero();
            }
            return $this->app->response->redirect("dice1/play");
        }





    /**
     * This is the index method action, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function debugAction() : string
    {
        // Deal with the action and return a response.
        return "Debug my game!";
    }
}
