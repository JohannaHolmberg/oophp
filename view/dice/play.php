<?php

namespace Anax\View;

/**
 * Render content within an article.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());

?>
<h2>Roll the dice, save or not</h2>

<div class="container">
    <div class="main-left-div">



        <div class="main-left-top-div">
            <h3>You rolled</h3>

            <?php if ($rolledDice) : ?>
                <!-- for each  -->
                <ul>
                <?php foreach ($playerHand as $dice) : ?>
                    <li><?= $dice->getValue() ?>
                <?php endforeach; ?>
                </ul>
                <!-- for each  END  -->

            <?php endif; ?>


            <!--  If a one is rolled this is printed out -->
            <?php if ($playerRollHasA1Value) : ?>
                <p> You rolled a 1 </p>
            <?php endif; ?>

        </div>



        <div class="main-left-bottom-div">
            <form method="post">
                <input class="button red-button" type="submit" name="roll" value="Roll">
                <input class="button green-button" type="submit" name="saved" value="Save">
                <input class="button blue-button" type="submit" name="reset" value="Reset">
            </form>
        </div>

    </div>




    <div class="main-right-div">
        <h3>Current Score</h3>
            <p>Player:</p>
            <?= $playerRoundScore ?>

            <p>Computer:</p>

        <?php if ($savedRound) : ?>
            <h3>Total Score</h3>
            <p>Player:</p>
            <?= $totPlayerScore ?>
            <p>Computer:</p>

        <?php endif; ?>


    </div>
</div> <!-- END container -->
