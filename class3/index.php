<?php session_start(); ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>PHP Hi-Low Game</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Cinzel" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/base.css" />
  </head>
  <body>
    <header>
      <h1>Paul Heintz</h1>
      <h4>Class 3 assignment</h4>
      <h4>1/30/2018</h4>
      <h4>Hi-Low game using PHP</h4>
    </header>
    <hr />

    <!-- About -->
    <p>
      This is the Hi-Low game developed in PHP. A number has been chosen between 1-100. 
      Keep guessing numbers and you will be told if your number is higher or 
      lower than the target number . See how many guesses it takes you to find the number!
    </p>

    <?php
      // Function to check if guess is within range
      // Returns true if within range, false with a javascript alert if not
      function rangeCheck($guess) {
          $inRange = true;
          if ($guess < 1 || $guess > 100) {
            $inRange = false;
          }
          return $inRange;
      } 

      // Function to check user's guess, increment the count, and add the guess to the prevGuesses array
      // Returns string "high", "low", or "equal" based on user's guess in respect to the target number
      function processGuess($guess) {
        $_SESSION['guessCount']++;

        $result = '';
        $prefix = '';
        if ((int)$guess === $_SESSION['targetNumber']) {
          $result = 'equal';
          $prefix = '<span class="success">';
        } else if ((int)$guess > $_SESSION['targetNumber']) {
          $result = 'high';
          $prefix = '<span class="alert">';
        } else {
          $result = 'low';
          $prefix = '<span class="primary">';
        }

        array_push($_SESSION['prevGuesses'], $prefix.$guess.'</span>');
        return $result;
      }

      // Check if game is being reset
      if (isset($_POST['reset'])) {
        session_unset();
        $_SESSION['guessResult'] = '';
        $_SESSION['gameOn'] = true;
      }

      // Check if session exists, set variables if not
      if (!isset($_SESSION['targetNumber'])) {
        $_SESSION['targetNumber'] = rand(1, 100);
        $_SESSION['guessCount'] = 0;
        $_SESSION['prevGuesses'] = array();
        $_SESSION['gameOn'] = true;
        $_SESSION['guessResult'] = '';
      }

      // Check if guess has been made
      if (isset($_POST['guess'])){
        // Check if within range
        if (rangeCheck($_POST['guess'])) {
          // Process user's guess
          $result = processGuess($_POST['guess']);
          if ($result === 'high') {
            $_SESSION['guessResult'] = '<p class="alert">'.$_POST['guess'].' is too high</p>';
          } else if ($result === 'low') {
            $_SESSION['guessResult'] = '<p class="primary">'.$_POST['guess'].' is too low</p>';
          } else {
            $_SESSION['guessResult'] = '<p class="success">Congratulations! '.$_POST['guess'].' is the correct number!<br />'
                                        .'It took you '.$_SESSION['guessCount'].' guesses.</p>';
            $_SESSION['gameOn'] = false;
          }
        } else {
            echo '<script>alert("** OUT OF RANGE **\nPlease enter a whole number between 1-100");</script>';
        }
      }
      // for testing
      //echo '<br />Target Number: '.$_SESSION['targetNumber'].'<br />';
    ?>

    <!-- Display the number of guesses -->
    <p>Number of guesses: <b><?php echo $_SESSION['guessCount']; ?></b></p>

    <!-- Form for user's guess -->
    <form action="index.php" method="post">
      <label>Guess a whole number between 1-100:</label><br />
      <input type="number" name="guess" <?php if($_SESSION['gameOn']){echo 'autofocus';} else {echo 'disabled';}?> />
      <input type="submit" value="Guess" <?php if(!$_SESSION['gameOn']){echo 'disabled';}?> />
    </form>

    <?php
      // Display guess result (if exists)
      if (!empty($_SESSION['guessResult'])) {
        echo $_SESSION['guessResult'];
      }
      // Display previous guesses (if exists)
      if (!empty($_SESSION['prevGuesses'])) {
        echo '<p>Your previous guesses: ';
        foreach ($_SESSION['prevGuesses'] as $oldGuess) {
          echo $oldGuess.' &nbsp; ';
        }
        echo '</p>';
      }
    ?>

    <br />
    <!-- Form to reset game (clear session variables) -->
    <form action="index.php" method="post">
      <label>Click here to restart: </label>
      <input type="hidden" name="reset" value="true">
      <input type="submit" value="Start Over">
    </form>

  </body>
</html>