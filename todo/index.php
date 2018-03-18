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
      <h4>Class 5 assignment</h4>
      <h4>2/20/2018</h4>
      <h4>Todo list using MySql database</h4>
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
      This is a web app for inserting todo activites into a MySql database.
    </p>

    <!-- Content -->
    <?php
    $params = [
      'Description'=>'',
      'Status'=>'Not started',
      'Priority'=>'Normal',
    ];
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $params = $_POST;
      if (empty($_POST['Description'])) {
        echo '<h4 class="alert">Description can not be empty!</h4>';
      } else {
        echo addRow($conn, $_POST);
        echo '<h4 class="primary">"'.$_POST['Description'].'" added to your list!</h4>';
        echo '<a href="index.php">Add another task</a> - <a href="todos.php">View my list</a>';
        exit();
      }
    }
    ?>

    <fieldset>
      <legend>Add a todo</legend>
      <form method="post" action="index.php">
        <table>
          <tr>
            <td><label>Description of your todo task: </label></td>
            <td><input type="text" name="Description" 
              <?php if (!empty($params['Description'])) {echo $params['Description'];} else {echo 'autofocus';} ?>></td>
          </tr>
          <tr>
            <td><label>Status of your task: </label></td>
            <td><select name="Status">
              <option value="Not started" 
                <?php if ($params['Status'] === "Not started") {echo 'selected';} ?>>Not Started</option>
              <option value="In progress" 
                <?php if ($params['Status'] === "In progress") {echo 'selected';} ?>>In progress</option>
              <option value="Completed" 
                <?php if ($params['Status'] === "Completed") {echo 'selected';} ?>>Completed</option>
              <option value="Canceled" 
                <?php if ($params['Status'] === "Canceled") {echo 'selected';} ?>>Canceled</option>
            </select></td>
          </tr>
          <tr>
            <td><label>Task Priority: </label></td>
            <td><select name="Priority">
              <option value="High" 
                <?php if ($params['Priority'] === "High") {echo 'selected';} ?>>High</option>
              <option value="Normal" 
                <?php if ($params['Priority'] === "Normal") {echo 'selected';} ?>>Normal</option>
              <option value="Low" 
                <?php if ($params['Priority'] === "Low") {echo 'selected';} ?>>Low</option>
            </select></td>
          </tr>
          <tr>
            <td></td>
            <td><input type="submit" value="Submit"></td>
          </tr>
        </table>
      </form>
    </fieldset>

  </body>
  </html>