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
    $_SESSION["computerRoundScore"] = $game->getRoundComputerScore();

    $_SESSION["playerHand"] = $round->getPlayerHand();
    $_SESSION["computerHand"] = $round->getComputerHand();

    // save game in session
    $_SESSION["game"] = $game;

    return $app->response->redirect("dice/play");
});


/**
 * Play route.
 */
$app->router->get("dice/play", function () use ($app) {
    $title = "Dice 100 game";
    // put gavecontroller in a variable
    $game = $_SESSION["game"];


    // GET VARIABLES FROM POST ROUTER


    // get total score for both players
    $totPlayerScore = $_SESSION["totPlayerScore"];
    $totComputerScore = $_SESSION["totComputerScore"];

    // get this rounds score for both players
    $playerRoundScore = $_SESSION["playerRoundScore"];
    $computerRoundScore = $_SESSION["computerRoundScore"];

    // get value of each rolled hand
    $pHand = $_SESSION["playerHand"];
    $cHand = $_SESSION["computerHand"];

    $round = $_SESSION["round"];

    // get value from Post buttons
    $roll = $_SESSION["roll"];
    $saved = $_SESSION["saved"];


    // when changed to true, it will mean that the save button has been clicked
    // send this variable to play.php
    $savedRound = false;


    /**
    *  Updates the TOTAL GAME SCORE when click SAVE
    */

    var_dump($_SESSION["roll"]);
    var_dump($_SESSION["saved"]);
    var_dump($saved);

    if ($saved) {
        $savedRound = true;

        var_dump($playerRoundScore);
        var_dump($totPlayerScore);

        $totPlayerScore = $game->updateTotPlayerScore($playerRoundScore);
        var_dump($totPlayerScore);

        $_SESSION["totPlayerScore"] = $totPlayerScore;
        var_dump($totPlayerScore);

        //set round score to 0
        $_SESSION["playerRoundScore"] = $game->setPlayerScoreToZero();
    }


    // ROLL clicked-------------------------------------------------------------

    // Before rolled dice, they hand is not shown on screen
    // It only show after roll button is clicked and it changes to TRUE
    $rolledDice = false;



    // if the button "roll" has been clicked, this should happen:
    if ($_SESSION["roll"]) {
        echo "rolled";
        // Changed the $rolledDice variable to TRUE to show that dice has ben rolled
        $rolledDice = true;

        $_SESSION["roll"] = null; // dont know why this is here?

        // check if there is a 1, if 1 then return TRUE
        $playerRollHasA1Value = $game->rollHasAValueOne($pHand->getDiceValue());

        /**
         * IF STATMENT for ROLL, to see if there has been rolled a 1!
         */

         // get the current round score OUTSIDE the if statment, to work??????????????????????????????????????????????????
         $playerRoundScore = $_SESSION["playerRoundScore"];
         $totPlayerScore = $_SESSION["totPlayerScore"];

        if ($playerRollHasA1Value) { // if true -> there is a 1 in the hand of dices
            //echo "You rolled a 1";

            // 1. set PlayerRoundscore to 0

            // set score to null or 0??
            //$playerRoundScore = 0;
            $game->setPlayerScoreToZero();

            //save to session?
            $playerRoundScore = 0;

            $_SESSION["playerRoundScore"] = 0;
            var_dump($_SESSION["playerRoundScore"]); // works, shows sum when not rolled 1 and shows 0 when 1 was rolled


            // 2. play for computer
                // - >




        } else { // No 1 in hand of dice, player won one round

            //echo "You did NOT roll a 1";

            // get the old score, from post
            //$playerRoundScore = $_SESSION["playerRoundScore"];

            // get the new round hand value sum score
            $prs = $game->sumHandValueForOneRound($pHand->getDiceValue());

            // update new with old score
            $playerRoundScore = $game->updateRoundPlayerScore($prs);
            var_dump($playerRoundScore);

            // save the round score in session.
            $_SESSION["playerRoundScore"] = $playerRoundScore;
        }

    }

    // put this in data to send to play.php
    //$playerRollHasA1Value = $game->rollHasAValueOne($pHand->getDiceValue());

    // put computers roll in SAVE and in ROLL where you got a 1!!!!!!!!!!!
    $computerRollHasA1Value = $game->rollHasAValueOne($cHand->getDiceValue());
    // -----------------------------------------------------------------------

    $data = [
        //"dices" => $dices ?? null,
        //"content" => "Hello World in " . __FILE__,
        "playerHand" => $pHand->getDiceValue() ?? "",
        "computerHand" => $cHand->getDiceValue() ?? "",
        "rolledDice" => $rolledDice,
        "playerRollHasA1Value" => $game->rollHasAValueOne($pHand->getDiceValue()),
        "playerRoundScore" => $playerRoundScore,
        "totPlayerScore" => $game->getTotPlayerScore(),
        "savedRound" => $savedRound

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
    $saved = $_POST["saved"] ?? null;
    //$reset = $_POST["reset"] ?? null;

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
        $_SESSION["computerRoundScore"] = $game->getRoundComputerScore();

        $_SESSION["playerHand"] = $round->getPlayerHand();
        $_SESSION["computerHand"] = $round->getComputerHand();

        // save round in session
        $_SESSION["round"] = $round;

        //$_SESSION["roll"] = $roll;
    }


    /**
    *  When SAVE button is clicked
    */
    if (isset($_POST["saved"])) {
        // save variable in session to use later
        // when save round score to tot score
        $game = $_SESSION["game"];
        $totPlayerScore = $game->getTotPlayerScore();
        $_SESSION["totPlayerScore"] = $totPlayerScore;

        //$_SESSION["saved"] = $saved;
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
