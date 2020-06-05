// ROLL clicked-------------------------------------------------------------

// // Before rolled dice, they hand is not shown on screen
// // It only show after roll button is clicked and it changes to TRUE
// $rolledDice = false;



// if the button "roll" has been clicked, this should happen:
// if ($_SESSION["roll"]) {
//
//     $rolledDice = true; // true to send to play.php, to say dice has rolled
//
//     // remove roll after rolled dice, otherwise the hand will always show
//     $_SESSION["roll"] = null;
//
//     // check if there is a 1, if 1 then return TRUE
//     $playerRollHasA1Value = $game->rollHasAValueOne($pHand->getDiceValue());
//
//     /**
//      * IF STATMENT for ROLL, to see if there has been rolled a 1!
//      */
//     if ($playerRollHasA1Value) { // if true -> there is a 1 in the hand of dices
//         // remove all points from player, set round player score to 0
//         $game->setPlayerScoreToZero();
//
//     } else { // No 1 in hand of dice, player won one round
//         // get the new round hand value sum score
//         $prs = $game->sumHandValueForOneRound($pHand->getDiceValue());
//
//         // update new with old score
//         $playerRoundScore = $game->updateRoundPlayerScore($prs);
//     }
//
// }
