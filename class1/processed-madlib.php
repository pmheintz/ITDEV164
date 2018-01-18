<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>PHP Madlib</title>
    <link href="https://fonts.googleapis.com/css?family=Cinzel" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/base.css" />
  </head>
  <body>
    <header>
      <h1>Paul Heintz</h1>
      <h4>Class 1 assignment</h4>
      <h4>1/16/2018</h4>
      <h4>Madlib using PHP</h4>
    </header>
    <hr />
    <h2>Stairway to Heaven... kind of</h2>

    <p>
      <?php
      // Check if all fields are populated
      if(count(array_filter($_REQUEST))!=count($_REQUEST)){
        echo "<p>* One or more fields were filled with a default word! *</p>";
      }

      // Assign values to variables
      if(!empty($_REQUEST['noun1'])) {$noun1 = $_REQUEST['noun1'];}
        else {$noun1 = 'lady';}
      if(!empty($_REQUEST['noun2'])) {$noun2 = $_REQUEST['noun2'];}
        else {$noun2 = 'gold';}
      if(!empty($_REQUEST['noun3'])) {$noun3 = $_REQUEST['noun3'];}
        else {$noun3 = 'stairway';}
      if(!empty($_REQUEST['location'])) {$location = $_REQUEST['location'];}
        else {$location = 'heaven';}
      if(!empty($_REQUEST['places'])) {$places = $_REQUEST['places'];}
        else {$places = 'stores';}

      // Display new lyrics
      echo "There's a <b>$noun1</b> who's sure<br />".PHP_EOL;
      echo "All that glitters is <b>$noun2</b><br />".PHP_EOL;
      echo "And she's buying a <b>$noun3</b> to <b>$location</b><br />".PHP_EOL;
      echo "When she gets there she knows<br />".PHP_EOL;
      echo "If the <b>$places</b> are all closed<br />".PHP_EOL;
      echo "With a word she can get what she came for<br />".PHP_EOL;
      echo "Oh oh oh oh and she's buying a <b>$noun3</b> to <b>$location</b><br />".PHP_EOL;
      ?>
    </p>

  </body>
</html>
