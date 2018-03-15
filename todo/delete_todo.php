<!DOCTYPE html>
<?php
// Include db connection (Used require because it terminates the script on error)
require_once('dbconn.php');
require_once('dbFunctions.php');
?>
<html>
  <head>
    <meta charset="UTF-8">
    <title>PHP todo list w/MySQL</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Cinzel" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/base.css" />
  </head>
  <body>
    <header>
      <h1>Paul Heintz</h1>
      <h4>Class 8 assignment</h4>
      <h4>3/20/2018</h4>
      <h4>Deleting from todo list</h4>
    </header>
    <hr />
    <nav>
      <?php
      // Insert navbar
      if (file_exists('navbar.php')) {
        include('navbar.php');
      } else {
        echo '<h4 class="alert">Navbar cannot be found!</h4>';
      }  ?>
    </nav>

    <!-- About -->
    <p>
      This is a web app for deleting todo activites from a MySql database.
    </p>

    <?php 
      // Check if id is set in get or post
      if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) {
        $fields = getSingleRow($conn, $_GET['id']);
      } else if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $fields = $_POST;
      } else {
        echo '<h4 class="alert">No todo id provided. Can not delete any records.</h4>';
        echo '<p><a href="todos.php">Return to todo list</a></p>';
        exit();
      }

      // Check if post
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Check if delete is confirmed
        if ($_POST['deleteConfirmed'] === 'yes') {
          // Delete row
          echo deleteRow($conn, $fields['id']);
          echo '<p><a href="todos.php">Return to todo list</a></p>';
        } else {
          // Delete canceled, return to todo list
          echo '<h4 class="primary">Delete canceled.</h4>';
          echo '<p>Redirecting to <a href="todos.php">todos</a> in 5 seconds...';
          echo '<script type="text/javascript">
                  setTimeout("window.location=\'todos.php\'",5000);
                </script>';
        }
      } else {
        // Display row to be deleted
        echo '<table>'.PHP_EOL;
        echo '<tr><td><b>Description: </b></td><td>'.$fields['Description'].'</td></tr>'.PHP_EOL;
        echo '<tr><td><b>Status: </b></td><td>'.$fields['Status'].'</td></tr>'.PHP_EOL;
        echo '<tr><td><b>Priority: </b></td><td>'.$fields['Priority'].'</td></tr>'.PHP_EOL;
        echo '</table>'.PHP_EOL;
        // Confirm delete
        echo '<form action="delete_todo.php" method="post">'.PHP_EOL;
        echo '<label>Delete this record?</label>&nbsp;'.PHP_EOL;
        echo '<input type="radio" name="deleteConfirmed" value="yes"> Yes &nbsp;'.PHP_EOL;
        echo '<input type="radio" name="deleteConfirmed" value="no" checked="checked"> No &nbsp;'.PHP_EOL;
        echo '<input type="hidden" name="id" value="'.$fields['id'].'">'.PHP_EOL;
        echo '<input type="submit" name="submit" value="Submit">'.PHP_EOL;
        echo '</form>';
      }
      $conn->close();
    ?>

  </body>
  </html>