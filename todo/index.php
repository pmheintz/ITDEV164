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

    <?php
    $requiredFieldAlert = '';
    $status = '';
    if (isset($_POST['Status'])) {$status = $_POST['Status'];}
    $priority = '';
    if (isset($_POST['Priority'])) {$priority = $_POST['Priority'];}
    if (isset($_POST['Description'])) {
      if (!empty($_POST['Description'])) {
        $requiredFieldAlert = '';
        $description = $_POST['Description'];
        echo addTodo($conn);
        // Clear $_POST
        $_POST = array();
        // Prompt for additional todo
        echo '<br /><form method="post" action="index.php">';
        echo '<label>Add another todo?</label> <input type="submit" value="Yes" />';
        echo '</form>';
        // Close DB connection
        $conn->close();
        // Exit script to prevent reloading page
        exit();
      } else {
        // Set alert to be displayed because description is empty
        $requiredFieldAlert = '<span class="alert">** Description cannot be empty **</span>';
      }
    }
    ?>

    <fieldset>
      <legend>Todo scheduler</legend>
      <form method="post" action="index.php">
        <table>
          <tr>
            <td><label>Description of your todo task: </label></td>
            <td><input type="text" name="Description"
              <?php
              // Put entered value into text field if exists, else autofocus on the field
              if (isset($_POST['Description']) && !empty($_POST['Description'])) {
                echo 'value="'.$_POST['Description'].'" ';
              } else {echo ' autofocus';} ?> />
            </td>
            <?php
            // Display error if description is empty
            echo $requiredFieldAlert;
            ?>
          </tr>
          <tr>
            <td><label>Status of your task: </label></td>
            <td><select name="Status">
              <option value="Not started"
                <?php if($status === "Not started" || empty($_POST['Status'])) {echo ' selected';} ?>
                >Not started</option>
              <option value="In progress"
                <?php if ($status === "In progress") {echo ' selected';} ?>
                >In progress</option>
              <option value="Complete"
                <?php if ($status === "Complete") {echo ' selected';} ?>
                >Complete</option>
              <option value="Canceled"
                <?php if ($status === "Canceled") {echo ' selected';} ?>
                >Canceled</option>
            </select></td>
          </tr>
          <tr>
            <td><label>Task priority: </label></td>
            <td><select name="Priority">
              <option value="High"
                <?php if ($priority === "High") {echo ' selected';} ?>
                >High</option>
              <option value="Normal"
                <?php if ($priority === "Normal" || empty($_POST['Priority'])) {echo ' selected';} ?>
                >Normal</option>
              <option value="Low"
                <?php if ($priority === "Low") {echo ' selected';} ?>
                >Low</option>
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
