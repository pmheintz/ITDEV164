<?php session_start();
      require 'gameController.php';
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>PHP Hi-Low Game with high scores</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Cinzel" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/base.css" />
  </head>
  <body>
    <header>
      <h1>Paul Heintz</h1>
      <h4>Class 4 assignment</h4>
      <h4>2/6/2018</h4>
      <h4>Hi-Low game with high scores using PHP</h4>
    </header>
    <hr />

    <!-- About -->
    <p>
      This is the Hi-Low game developed in PHP. A number has been chosen between 1-100.
      Keep guessing numbers and you will be told if your number is higher or
      lower than the target number . See how many guesses it takes you to find the number!
      This new version accesses a file to save and display high scores.
    </p>
    <h3>High Scores</h3>
    <div class="resultSet">
      <table>
        <tr>
          <th>Player Name</th><th>Guesses</th>
        </tr>
        <?php displayHighScores(); ?>
      </table>
    </div>
    <hr />

    <!-- Display the number of guesses -->
    <p>Number of guesses: <b><?php echo $_SESSION['guessCount']; ?></b></p>

    <form action="index.php" method="post">
      <label>Please enter your name:</label><br />
      <input type="text" name="playerName" id="player"
      <?php if (!empty($_SESSION['playerName'])) {
        echo 'value="'.$_SESSION['playerName'].'" disabled';
      } else {
        echo 'autofocus';
      } ?> /><br /><br />
      <label>Guess a whole number between 1-100:</label><br />
      <input type="number" name="guess" id="guessTxt"
        <?php if($_SESSION['gameOn'] && !empty($_SESSION['playerName'])){echo 'autofocus';} else {echo 'disabled';}?> />
      <input type="submit" value="Guess" id="guessBtn" <?php if(!$_SESSION['gameOn']){echo 'disabled';}?> />
    </form>
    <br />

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

    <!-- Form to reset game (clear session variables) -->
    <form action="index.php" method="post">
      <label>Click here to restart: </label>
      <input type="hidden" name="reset" value="true">
      <input type="submit" value="Start Over">
    </form>

    <script type="text/javascript">
      // Script to ensure that a player name is entered before allowing guesses
      $(document).ready(function(){
        // Disable guess button and text field if player name is empty
        if ($('#player').val().length == 0) {
          $('#guessTxt').prop('disabled', true);
          $('#guessBtn').prop('disabled', true);
        }
        // On keyup on player name, enable guess fields if player name is not empty
        $('#player').keyup(function() {
          if ($('#player').val().length != 0) {
            $("#guessTxt").prop("disabled", this.value == "" ? true : false);
            $("#guessBtn").prop("disabled", this.value == "" ? true : false);
          }
        });
      });
    </script>

  </body>
</html>
