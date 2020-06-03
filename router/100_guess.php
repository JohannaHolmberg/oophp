<?php
/**
 * Create routes using $app programming style.
 */
//var_dump(array_keys(get_defined_vars()));



/**
 * Init the game and redirect to play the game.
 */
$app->router->get("guess/init", function () use ($app) {

    // Initiate the game.
    $game = new Jo\Guess\Guess();
    $_SESSION["number"] = $game->number();
    $_SESSION["tries"] = $game->tries();

    // save game in session
    $_SESSION["game"] = $game;

    return $app->response->redirect("guess/play");
});



/**
 * GET Play the game. - Show game status
 */
$app->router->get("guess/play", function () use ($app) {
    $title = "Play the game";

    // get current settings from SESSION
    $game = $_SESSION["game"];

    $tries = $_SESSION["tries"] ?? null;
    $res = $_SESSION["res"] ?? null;

    $guess = $_SESSION["guess"] ?? null;
    $number = $_SESSION["number"] ?? null;
    $doCheat = $_SESSION["doCheat"] ?? null;

    $correctAnswer = $_SESSION["correctAnswer"] ?? null;

    $correctNumber = null;
    $triesToLow = null;


    // after using value, set to null.
    $_SESSION["res"] = null;
    $_SESSION["guess"] = null;


    if ($tries < 1) {
        $triesToLow = $game->tries();
        $tries = $game->tries();
    }

    // print out cheat number if "cheat" button is clicked
    if ($_SESSION["doCheat"]) {
        $correctNumber = $game->number();
    }

    // after showing the correct number, remove the request to see it.
    $_SESSION["doCheat"] = false;

    $data = [
        "guess" => $guess ?? null,
        "tries" => $tries,
        "number" => $number ?? null,
        "res" => $res,
        "correctNumber" => $correctNumber,
        "triesToLow" => $triesToLow,
        "correctAnswer" => $correctAnswer,
        "doGuess" => $doGuess ?? null,
        "doCheat" => $doCheat ?? null,
        "doInit" => $doInit ?? null,
    ];

    $app->page->add("guess/play", $data);
    $app->page->add("guess/debug");

    return $app->page->render([
        "title" => $title,
    ]);
});


/**
 * POST Play the game. - make a guess
 */
$app->router->post("guess/play", function () use ($app) {
    $title = "Play the game";

    // Deal with incoming variables
    $guess = $_POST["guess"] ?? null;
    $doInit = $_POST["doInit"] ?? null;
    $doGuess = $_POST["doGuess"] ?? null;
    $doCheat = $_POST["doCheat"] ?? null;

    // get current settings from SESSION
    $number = $_SESSION["number"] ?? null;
    $tries = $_SESSION["tries"] ?? null;
    $correctAnswer = $_SESSION["correctAnswer"] ?? null;

    $res = null;
    $correctAnswer = null;

    // if click guess and still have guesses
    if (isset($_POST["doGuess"]) && $tries > 0) {
        // see if guess is correct or not
        if ($guess == $number) {
            // correct answer
            // make a guess
            $game = new Jo\Guess\Guess($number, $tries);
            $res = $game->makeGuess($guess);

            // saves number of tries left
            $_SESSION["tries"] = 0; // set treis to 0 - user can't guess again.

            // saves last res, last guessed string
            $_SESSION["res"] = $res;

            // save last guess, to show in get
            $_SESSION["guess"] = $guess;
        } else {
            // make a guess
            $game = new Jo\Guess\Guess($number, $tries);
            $res = $game->makeGuess($guess);

            // saves number of tries left
            $_SESSION["tries"] = $game->tries();

            // saves last res, last guessed string
            $_SESSION["res"] = $res;

            // save last guess, to show in get
            $_SESSION["guess"] = $guess;
        }
    }

    // cheat button to see right number
    if (isset($_POST["doCheat"])) {
        $_SESSION["doCheat"] = $doCheat;
    }

    if (isset($_POST["doInit"])) {
        // Initiate the game.
        $game = new Jo\Guess\Guess();
        $_SESSION["number"] = $game->number();
        $_SESSION["tries"] = $game->tries();

        // save game in session
        $_SESSION["game"] = $game;
    }

    return $app->response->redirect("guess/play");
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
