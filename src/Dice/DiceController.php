<?php

namespace Jo\Dice;

use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;

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
        $session->set("computerHasPlayed", false);

        //remove COMPUTER histogram
        $computerRollH = "";
        $session->set("computerAllRollHistogramArray", $computerRollH);

        // PLAYER
        // create a new DiceHistogram object from class
        // to use to show histogram in game
        $hVm = new DiceHistogram();
        $session->set("hV", $hVm);

        // COMPUTER
        $cHistogram = new DiceHistogram();
        $session->set("cH", $cHistogram);

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
        $hVm = $session->get("hV"); // get the histogram class object

        // when changed to true, it will mean that the save button has been clicked
        //$savedRound = false;
        // Before rolled dice, they hand is not shown on screen
        // It only show after roll button is clicked and it changes to TRUE
        $rolledDice = false;
        $computerHasPlayed = false;

        $playerWon = false; // player wins when this is changed to true
        $computerWon = false; // computer wins when this is changed to true

        $computerRoll1Value = false;
        $playerRollHasA1Value = false;

        $totComputerScore =  $session->get("totComputerScore");
        $playerRollHasA1Value =  $session->get("playerRollHasA1Value");
        $computerRoll1Value =  $session->get("computerRollHasA1Value");

        /**
        *
        * if the button "ROLL" for PLAYER has been clicked, this happens:
        *
        */
        if ($session->get("roll")) {
            // ---- START player histogram -------------------------------
             $hVm->roll($pHand->getDiceValue()); // get current roll
             $returnPrintHistogram = $hVm->printHistogram(1, 6); // return string with roll ****
             $hVm->setplayerHistogramString($returnPrintHistogram); //$playerOld . $returnPrintHistogram;
            // ---- END player histogram -----------------------------------------

            $computerRoll1Value = false; // remove computer roll text
            $session->set("computerRollHasA1Value", $computerRoll1Value);

            $rolledDice = true; // The PLAYER ROLL button has been clicked:
            $session->set("roll", null);// remove roll after rolled dice, otherwise the hand will always show

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
                $prs = $game->sumHandValueForOneRound($pHand->getDiceValue()); // get the new round hand value sum score
                $game->updateRoundPlayerScore($prs);// update new with old score
            }

            // check here if tot points is more than 100
            $roundScore = $game->getRoundPlayerScore();
            $totScore = $game->getTotPlayerScore();

            if ($game->isBigger100($roundScore + $totScore)) { // create variables here to send to play.php
                $playerWon = true;
                $session->set("playerWon", $playerWon);
            }
        } // end PLAYER "roll"

        $totComputerScore = $game->getTotComputerScore();     // zero out computer rolls values

        if ($game->isBigger100($totComputerScore)) { // check here if tot points is more than 100
            $session->set("computerWon", true);
            $computerWon = $session->get("computerWon");
        }

        $cHistogram = $session->get("cH");
        $computerHasPlayed = $session->get("computerHasPlayed");

        $data = [
            "playerHand" => $pHand->getDiceValue() ?? "",
            "rolledDice" => $rolledDice,
            "savedRound" => $session->get("savedRound"),
            "playerRoundScore" => $game->getRoundPlayerScore(),
            "totPlayerScore" => $game->getTotPlayerScore(),
            "totComputerScore" => $game->getTotComputerScore(),
            "playerRollHasA1Value" => $playerRollHasA1Value,
            "computerRolledOne" => $computerRoll1Value,
            "playerWon" => $playerWon,
            "computerWon" => $computerWon,
            "playerAllRollHistogramArray" => $hVm->getplayerHistogramString() ?? "",
            "printComputerHistogramValue" => $cHistogram->getcomputerHistogramString()?? "",
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
        $request = $this->app->request;
        $session = $this->app->session;

        $roll = $request->getPost("roll");
        $simulateComputer = $request->getPost("simulateComputer");
        /***  When COMPUTER simulation is clicked  **/
        if ($request->getPost("simulateComputer")) {
            $cHistogram = $session->get("cH"); // get the histogram class object for computer
            $computerHasPlayed = true;// SIMULATE COMPUTER BUTTON has been clicked:
            $session->set("computerHasPlayed", $computerHasPlayed);
            $session->set("simulateComputer", $simulateComputer);
            $session->set("simulateComputer", null);
            $playerRollHasA1Value = false; // default value, turn to true when a roll has a value 1
            $session->set("playerRollHasA1Value", $playerRollHasA1Value); // remove player text
            $round = new GameRound(); // start a new round with the GameRound
            $game = $_SESSION["game"]; // save game object in variable to use later
            $cHistogramand = $round->getComputerHand(); // save computer hand rolled
            $session->set("cHand", $cHistogramand); // set the value of current roll to a session
            // ---- START COMPUTER histogram -----------------------------------------
            $histogramValues = $cHistogram->roll($cHistogramand->getDiceValue()); // get current roll
            $returnPrintHistogram = $cHistogram->printHistogram(1, 6); // return string with roll ****
            $cHistogram->setcomputerHistogramString($returnPrintHistogram);

            /** IF STATMENT for COMPUTER ROLL, to see if there has been rolled a 1! */
            if ($game->rollComputerHasAValueOne($cHistogramand->getDiceValue())) {  // if true -> there is a 1 in the hand of dices
                // remove all points from player, set round player score to 0
                $game->setComputerScoreToZero();
                $computerRoll1Value = $game->rollComputerHasAValueOne($cHistogramand->getDiceValue());
                $session->set("computerRollHasA1Value", $computerRoll1Value);
            } else {  // No 1 in hand of dice,
                // get the new round hand value sum score
                $prs = $game->sumHandValueForOneRound($cHistogramand->getDiceValue());
                $computerRoundScore = $game->updateRoundComputerScore($prs); // update new with old score
                $totCScore = $game->getTotComputerScore();
                $ran;
                if ($totCScore >= 0) {
                    $ran = rand(1, 3); // 33% chance computer rolls again
                } elseif ($totCScore > 50) {
                    $ran = rand(1, 2); // 50% chance computer rolls again
                }

                if ($ran === 1) { // computer wants to roll again
                    $round = new GameRound();
                    // 2nd roll, even if a one is here, it should show histogram
                    $histogramValues = $cHistogram->roll($cHistogramand->getDiceValue()); // get current roll
                    $returnPrintHistogram = $cHistogram->printHistogram(1, 6); // return string with roll ****
                    $cHistogram->setcomputerHistogramString($returnPrintHistogram);

                    if ($game->rollComputerHasAValueOne($cHistogramand->getDiceValue())) {  // if true -> there is a 1 in the hand of dices
                        $game->setComputerScoreToZero();// remove all points from player, set round player score to 0
                        $computerRoll1Value = $game->rollComputerHasAValueOne($cHistogramand->getDiceValue());
                        $session->set("computerRollHasA1Value", $computerRoll1Value);
                    } else {
                        $prs = $game->sumHandValueForOneRound($cHistogramand->getDiceValue()); // get the new round hand value sum score
                        $computerRoundScore = $game->updateRoundComputerScore($prs); // update new with old score
                        $totComputerScore = $game->updateTotComputerScore($computerRoundScore); // update totComputerScore
                        $computerRoundScore = $game->setComputerScoreToZero(); //set round score to 0
                    }
                } elseif ($ran === 2) {  // computer wants to save the first roll
                    $totComputerScore = $game->updateTotComputerScore($computerRoundScore); // update totComputerScore
                    $computerRoundScore = $game->setComputerScoreToZero(); //set round score to 0
                }
            }
        } // end "computer roll"

        /**
        *  When PLAYER ROLL button is clicked
        */
        if ($request->getPost("roll")) {
            $session->set("computerAllRollHistogramArray", "");
            // start a new round with the GameRound
            $round = new GameRound();
            $game = $session->get("game");

            $session->set("playerRoundScore", $game->getRoundPlayerScore());
            $session->set("playerHand", $round->getPlayerHand());

            $session->set("round", $round); // save round in session
            $session->set("roll", $roll);
        }

        /**
        *  When SAVE button is clicked
        */
        if ($request->getPost("save")) {
            $game = $session->get("game"); // get game object from session
            $session->set("savedRound", true);//save in session, to send to play.php;

            // get RoundScore for both players, to update to tot score
            $playerRoundScore = $game->getRoundPlayerScore();
            $game->updateTotPlayerScore($playerRoundScore); // update totPlayerScore
            $playerRoundScore = $game->setPlayerScoreToZero(); //set round score to 0
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
