<?php

namespace Anax\View;

/**
 * Render content within an article.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());

?>

<h1>Guess my number</h1>
<p>Guess a number between 1 and 100, you have 6 guesses</p>

<form method="post">
   <input type="text" name="guess">

   <input type="submit" name="doGuess" value="Make a guess">
   <input type="submit" name="doCheat" value="Cheat">
   <input type="submit" name="doInit" value="Restart">
</form>


<!-- if res has a value, print it out -->
<?php if ($res) : ?>
    <p>Your guess <?= $guess ?> is <b><?= $res ?></b></p>
<?php endif; ?>

<?php if ($correctNumber) : ?>
    <p> Correct number is <?= $correctNumber ?> </p>
<?php endif; ?>

<?php if ($triesToLow) : ?>
    <p> You have no more guesses </p>
<?php endif; ?>
