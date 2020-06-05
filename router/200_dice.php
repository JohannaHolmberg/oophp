<?php
/**
 * Create routes using $app programming style.
 */
//var_dump(array_keys(get_defined_vars()));



/**
 * Init the game and redirect to play the game.
 */
$app->router->get("dice/init", function () use ($app) {
    // start the game with the GameController
    $game = new Jo\Dice\GameController();

    $_SESSION["totPlayerScore"] = $game->getTotPlayerScore();
    $_SESSION["totComputerScore"] = $game->getTotComputerScore();

    // create a round on init. Then on roll create a new.
    $round = new Jo\Dice\GameRound();

    $_SESSION["playerRoundScore"] = $game->getRoundPlayerScore();
    $_SESSION["playerHand"] = $round->getPlayerHand();

    // save game in session
    $_SESSION["game"] = $game;

    return $app->response->redirect("dice/play");
});


/**
 * Play route.
 */
$app->router->get("dice/play", function () use ($app) {
    $title = "Dice 100 game";

    $game = $_SESSION["game"]; // put gavecontroller in a variable
    $pHand = $_SESSION["playerHand"]; // get value of each rolled hand
    //$roll = $_SESSION["roll"]; // get value from Post buttons

    // hide roll to see if message dissapears on student server




    // when changed to true, it will mean that the save button has been clicked
    // send this variable to play.php
    $savedRound = false;

    // Before rolled dice, they hand is not shown on screen
    // It only show after roll button is clicked and it changes to TRUE
    $rolledDice = false;


    //$playerWon = $_SESSION["playerWon"];
    $playerWon = false; // player wins when this is changed to true
    $computerWon = false; // computer wins when this is changed to true

    $computerRollHasA1Value = false;
    $playerRollHasA1Value = false;

    $totComputerScore = $_SESSION["totComputerScore"];
    $playerRollHasA1Value = $_SESSION["playerRollHasA1Value"];
    $computerRollHasA1Value = $_SESSION["computerRollHasA1Value"];


    /**
    *
    * if the button "ROLL" has been clicked, this happens:
    *
    */
    if ($_SESSION["roll"]) {
        // remove computer roll text

        $computerRollHasA1Value = false;
        $_SESSION["computerRollHasA1Value"] = $computerRollHasA1Value;



        $rolledDice = true; // true to send to play.php, to say dice has rolled

        // remove roll after rolled dice, otherwise the hand will always show
        $_SESSION["roll"] = null;

        // check if there is a 1, if 1 then return TRUE
        $playerRollHasA1Value = $game->rollHasAValueOne($pHand->getDiceValue());
        $_SESSION["playerRollHasA1Value"] = $playerRollHasA1Value;

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
            $_SESSION["playerWon"] = $playerWon;
        }
    } // end "roll"

    $totComputerScore = $game->getTotComputerScore();

    if ($game->isBigger100($totComputerScore)) { // check here if tot points is more than 100
        $_SESSION["computerWon"] = true;
        $computerWon = $_SESSION["computerWon"];
    }







    $data = [
        //"content" => "Hello World in " . __FILE__,
        "playerHand" => $pHand->getDiceValue() ?? "",
        "rolledDice" => $rolledDice,
        "savedRound" => $_SESSION["savedRound"],
        "playerRoundScore" => $game->getRoundPlayerScore(),
        "totPlayerScore" => $game->getTotPlayerScore(),
        "totComputerScore" => $game->getTotComputerScore(),
        "playerRollHasA1Value" => $playerRollHasA1Value,
        "computerRolledOne" => $computerRollHasA1Value,
        "playerWon" => $playerWon,
        "computerWon" => $computerWon
    ];

    $app->page->add("dice/play", $data);

    return $app->page->render(["title" => $title,]);
});




/**
 * POST Play the game. - make a guess
 */
$app->router->post("dice/play", function () use ($app) {
    $title = "Play the game";

    // Deal with incoming variables
    $roll = $_POST["roll"] ?? null;
    $save = $_POST["save"] ?? null;
    $simulateComputer = $_POST["simulateComputer"] ?? null;


    /**
    *
    *  When COMPUTER simulation is clicked
    *
    */
    if (isset($_POST["simulateComputer"])) {
        $playerRollHasA1Value = false;
        $_SESSION["playerRollHasA1Value"] = $playerRollHasA1Value; // remove player text

        $round = new Jo\Dice\GameRound(); // start a new round with the GameRound

        $game = $_SESSION["game"]; // save game object in variable to use later
        $cHand = $round->getComputerHand(); // save computer hand rolled

        $_SESSION["cHand"] = $cHand;
        /**
         * IF STATMENT for COMPUTER ROLL, to see if there has been rolled a 1!
         */
        if ($game->rollComputerHasAValueOne($cHand->getDiceValue())) {  // if true -> there is a 1 in the hand of dices
            // remove all points from player, set round player score to 0
            $game->setComputerScoreToZero();


            $computerRollHasA1Value = $game->rollComputerHasAValueOne($cHand->getDiceValue());
            $_SESSION["computerRollHasA1Value"] = $computerRollHasA1Value;
        } else {  // No 1 in hand of dice,
            // get the new round hand value sum score
            $prs = $game->sumHandValueForOneRound($cHand->getDiceValue());
            $computerRoundScore = $game->updateRoundComputerScore($prs); // update new with old score

            $ran = rand(1, 2); // get a random number to see if computer wants to roll again or save

            if ($ran === 1) { // computer wants to roll again
                $round = new Jo\Dice\GameRound();

                if ($game->rollComputerHasAValueOne($cHand->getDiceValue())) {  // if true -> there is a 1 in the hand of dices
                    // remove all points from player, set round player score to 0
                    $game->setComputerScoreToZero();

                    $computerRollHasA1Value = $game->rollComputerHasAValueOne($cHand->getDiceValue());
                    $_SESSION["computerRollHasA1Value"] = $computerRollHasA1Value;
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
    }









    /**
    *  When ROLL button is clicked
    */
    if (isset($_POST["roll"])) {
        // start a new round with the GameRound
        // Contains counts for both players and
        // new hands/rolls for both players
        $round = new Jo\Dice\GameRound();

        $game = $_SESSION["game"];

        $_SESSION["playerRoundScore"] = $game->getRoundPlayerScore();
        $_SESSION["playerHand"] = $round->getPlayerHand();

        // save round in session
        $_SESSION["round"] = $round;

        $_SESSION["roll"] = $roll;
    }


    /**
    *  When SAVE button is clicked
    */
    if (isset($_POST["save"])) {
        $game = $_SESSION["game"]; // get game object from session
        $_SESSION["savedRound"] = true; //save in session, to send to play.php;

        // get RoundScore for both players, to update to tot score
        $playerRoundScore = $game->getRoundPlayerScore();

        // update totPlayerScore
        $totPlayerScore = $game->updateTotPlayerScore($playerRoundScore);

        //set round score to 0
        $playerRoundScore = $game->setPlayerScoreToZero();
    }

    return $app->response->redirect("dice/play");
});

























































/**
* Showing message Hello World, rendered within the standard page layout.
 */
$app->router->get("lek/hello-world-page", function () use ($app) {
    $title = "Hello World as a page";
    $data = [
        "class" => "hello-world",
        "content" => "Hello World in " . __FILE__,
    ];

    $app->page->add("anax/v2/article/default", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});
