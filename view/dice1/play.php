<?php

namespace Anax\View;

/**
 * Render content within an article.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());

?>
<h2>1. Roll the dice, save or not</h2>

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


            <!--  PLAYER If a dice with value 1 is rolled this is printed out -->
            <?php if ($playerRollHasA1Value) : ?>
                <p> You rolled a 1, play for the computer now! </p>
            <?php endif; ?>

            <!-- COMPUTER If a dice with value 1 is rolled this is printed out -->
            <?php if ($computerRolledOne) : ?>
                <p> Computer rolled a 1 </p>
            <?php endif; ?>


            <!--  if player has 100 or more points -->
            <?php if ($playerWon) : ?>
                <p> YOU WON!!!!! </p>
            <?php endif; ?>

            <!--  if player has 100 or more points -->
            <?php if ($computerWon) : ?>
                <p> COMPUTER WON!!!!! </p>
            <?php endif; ?>

        </div>



        <div class="main-left-bottom-div">
            <form method="post">
                <input class="button red-button" type="submit" name="roll" value="Roll">
                <input class="button green-button" type="submit" name="save" value="Save">
                <input class="button red-button" type="submit" name="simulateComputer" value="Play for computer">
            </form>


            <div class="main-left-bottom-div-histogram">
                <div class="main-left-bottom-div-player">
                    <?php if ($rolledDice) : ?>
                        <!-- When player plays -->
                        <?php foreach ($playerAllRollHistogramArray as $hand) : ?>
                                <p> <?= $hand ?> </p>

                        <?php endforeach; ?>

                    <?php endif; ?>
                </div>

                <div class="main-left-bottom-div-computer">
                    <?php if ($computerHasPlayed) : ?>
                        <!-- When computer plays -->
                            <?php foreach ($printComputerHistogramValue as $hand) : ?>
                                <p> <?= $hand ?> </p>

                            <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            </div>







        </div>

    </div>

    <!-- Right side, where the total score board is -->
    <div class="main-right-div">
        <h3>Current Score</h3>
            <?= $playerRoundScore ?>

        <h3>Total Score</h3>

        <?php if ($savedRound || $totComputerScore) : ?>
            <p>Player:</p>
            <?= $totPlayerScore ?>

            <p>Computer:</p>
            <?= $totComputerScore ?>
        <?php endif; ?>

    </div>
</div> <!-- END container -->
