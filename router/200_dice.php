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
