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
    <script type="text/javascript">
      function changeRowsDisplayed(rows) {
        document.location = "todos.php?rows=" + rows;
      }
    </script>
  </head>
  <body>
    <header>
      <h1>Paul Heintz</h1>
      <h4>Class 6/7 assignments</h4>
      <h4>2/27/2018</h4>
      <h4>Todo list results using MySql database with sorting and pagination</h4>
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
      This is a web app for displaying todo activites from a MySql database.
    </p>

    <form name="rowForm">
      <label>Results per page: </label>
      <select name="rows" id="rows" onChange="changeRowsDisplayed(this.value);">
        <option value="all" <?php if (!isset($_GET['rows']) || $_GET['rows'] === 'all')
                            { echo 'selected="selected"'; } ?>>All</option>
        <option value="1" <?php if (isset($_GET['rows']) && $_GET['rows'] === '1')
                            { echo 'selected="selected"'; } ?>>1</option>
        <option value="2" <?php if (isset($_GET['rows']) && $_GET['rows'] === '2')
                            { echo 'selected="selected"'; } ?>>2</option>
        <option value="3" <?php if (isset($_GET['rows']) && $_GET['rows'] === '3')
                            { echo 'selected="selected"'; } ?>>3</option>
        <option value="5" <?php if (isset($_GET['rows']) && $_GET['rows'] === '5')
                            { echo 'selected="selected"'; } ?>>5</option>
        <option value="10" <?php if (isset($_GET['rows']) && $_GET['rows'] === '10')
                            { echo 'selected="selected"'; } ?>>10</option>
        <option value="25" <?php if (isset($_GET['rows']) && $_GET['rows'] === '25')
                            { echo 'selected="selected"'; } ?>>25</option>
      </select>
    </form>
    <br />

    <?php
    getAllTodos($conn);
    $conn->close();
     ?>
  </body>
  </html>
