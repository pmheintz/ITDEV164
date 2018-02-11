<?php

// Controller for php hi-low game

// Function to check if guess is within range
// Returns true if within range, false if not
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

// Function to display high scores from file
function displayHighScores() {
	if (file_exists('../../uploads/highscores.txt')) {
		// Read the file data into an array
		// Strip new lines because they are added with implode() 
		// Skip empty lines (if written)
		$_SESSION['highScores'] = file('../../uploads/highscores.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		foreach ($_SESSION['highScores'] as $val) {
			$scoreAndName = explode(' ', $val, 2); // Limit indices to 2 in case a full name was used
			$name = array_pop($scoreAndName);
			$score = implode(' ', $scoreAndName);
			echo '<tr><td>'.$name.'</td><td>'.$score.'</td></tr>'.PHP_EOL;
		}
	} else {
		echo 'No high scores yet!';
	}
}

// Function to check if high score and update highscores.txt
function updateHighScores() {
	$currentGame = $_SESSION['guessCount'].' '.trim($_SESSION['playerName']);
	if (!isset($_SESSION['highScores'])) {
		$_SESSION['highScores'] = array();
	}
	array_push($_SESSION['highScores'], $currentGame);
	natsort($_SESSION['highScores']);
	while (sizeof($_SESSION['highScores']) > 5) {
		array_pop($_SESSION['highScores']);
	}
	writeHighScores();
}

// Function to write the high scores to a file
function writeHighScores() {
	file_put_contents('../../uploads/highscores.txt', implode("\n", $_SESSION['highScores']));
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
	$_SESSION['playerName'] = '';
}

// Store player name in session
if (isset($_POST['playerName'])) {
	if (empty($_SESSION['playerName'])) {
		$_SESSION['playerName'] = $_POST['playerName'];
	} 
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
            updateHighScores();
			$_SESSION['gameOn'] = false;
		}
    } else {
		echo '<script>alert("** OUT OF RANGE **\nPlease enter a whole number between 1-100");</script>';
    }
}

?>