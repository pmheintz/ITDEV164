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

    <!-- Content -->

    <?php
    // Determine if the database has records
    $records = getNumRows($conn);
    if ($records === 0) {
      echo '<h4 class="alert">No tasks yet. Please add a todo.</h4>'.PHP_EOL;
      echo '<p><a href="index.php">Add a todo</a>'.PHP_EOL.'</body>'.PHP_EOL.'</html>';
      exit();
    }
    ?>

    <!-- Dropdown to select how many results are displayed per page -->
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
    // Determine number of pages for pagination
    if (isset($_GET['rows'])) {
      $rowsDisplayed = $_GET['rows'];
    } else {
      $rowsDisplayed = 'all';
    }
    if (!is_numeric($rowsDisplayed)) {$rowsDisplayed = 1000000;}
    $pages = getNumPages($conn, $rowsDisplayed);

    // Determine start point for pagination
    if (isset($_GET['start'])) {
      $start = $_GET['start'];
    } else {
      $start = 0;
    }

    // Get sorting parameters if any
    $direction = (isset($_GET['direction'])) ? $_GET['direction'] : 'ASC';
    $sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'id';

    if ($pages !== 0) {
      // Set sorting direction (ascending/descending) for header links
      $directionForHeaders = ($direction === 'DESC') ? 'ASC' : 'DESC';

      // Display table header
      echo '<div class="resultSet">'.PHP_EOL.'<table>'.PHP_EOL;
      echo '<tr>'.PHP_EOL;
      echo '<th></th><th><a href="todos.php?rows='.$rowsDisplayed.'&sort=id&direction=';
      if ($sort !== 'id') { echo 'ASC'; } else { echo $directionForHeaders; }
      echo '">ID</a></th>'.PHP_EOL;
      echo '<th><a href="todos.php?rows='.$rowsDisplayed.'&sort=Description&direction=';
      if ($sort !== 'Description') { echo 'ASC'; } else { echo $directionForHeaders; }
      echo '">Description</a></th>'.PHP_EOL;
      echo '<th><a href="todos.php?rows='.$rowsDisplayed.'&sort=Status&direction=';
      if ($sort !== 'Status') { echo 'ASC'; } else { echo $directionForHeaders; }
      echo '">Status</a></th>'.PHP_EOL;
      echo '<th><a href="todos.php?rows='.$rowsDisplayed.'&sort=Priority&direction=';
      if ($sort !== 'Priority') { echo 'ASC'; } else { echo $directionForHeaders; }
      echo '">Priority</a></th>'.PHP_EOL.'</tr>'.PHP_EOL;

      // If only one page, display all rows
      if ($pages === 1) {
        // Get all the todos
        $result = getAllTodos($conn, $sort, $direction);
      } else {
        // Get todos with additional variables
        $result = getTodosWithRange($conn, $sort, $direction, $start, $rowsDisplayed);
      }

      // Display table data
      while ($row = $result->fetch_assoc()) {
        echo '<tr>'.PHP_EOL;
        echo '<td><a href="edit_todo.php?id='.$row['id'].'">Update</a> &nbsp; <a href="delete_todo.php?id='
              .$row['id'].'">Delete</a></td>'.PHP_EOL;
        echo '<td>'.$row['id'].'</td><td>'.$row['Description'].'</td><td>'
              .$row['Status'].'</td><td>'.$row['Priority'].'</td>'.PHP_EOL.'</tr>'.PHP_EOL;
      }
      echo '</table>'.PHP_EOL.'</div>'.PHP_EOL;
    } else {
      echo '<h4 class="alert">No tasks found in the database.</h4>';
    }

    // HTML for pagination page links
    $currentPage = 0;
    if ($pages > 1) {
      // Set current page
      $currentPage = ($start / $rowsDisplayed) + 1;
      echo '<div class="pagination">'.PHP_EOL.'<ul>'.PHP_EOL;

      // Set the previous button
      if ($currentPage !== 1) {
        echo '<li><a href="todos.php?rows='.$rowsDisplayed.'&start='.($start - $rowsDisplayed).'&pages='.$pages
            .'&sort='.$sort.'&direction='.$direction.'">Previous </a></li>'.PHP_EOL;
      } else {
        echo '<li><span style="color: gray;">Previous </span></li>'.PHP_EOL;
      }

      // Set page numbers
      for ($i = 1; $i <= $pages; $i++) {
        if ($i != $currentPage) {
          echo '<li><a href="todos.php?rows='.$rowsDisplayed.'&start='.($rowsDisplayed * ($i - 1)).'&pages='.$pages
                .'&sort='.$sort.'&direction='.$direction.'">'.$i.'</a></li>'.PHP_EOL;
        } else {
          echo '<li class="current">'.$i.'</li>'.PHP_EOL;
        }
      }

      // Set the next button
      if ($currentPage != $pages) {
        echo '<li><a href="todos.php?rows='.$rowsDisplayed.'&start='.($start + $rowsDisplayed).'&pages='.$pages
            .'&sort='.$sort.'&direction='.$direction.'"> Next</a></li>'.PHP_EOL;
      } else {
        echo '<li><span style="color: gray;"> Next</span></li>'.PHP_EOL;
      }

      echo '</ul>'.PHP_EOL.'</div>';
      $conn->close();
    }

    ?>

  </body>
  </html>