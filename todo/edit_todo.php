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
      <h4>Updating todo list</h4>
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
      This is a web app for updating todo activites from a MySql database.
    </p>

    <?php
    $update = false;
    $fields = [];
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    	$fields = getSingleRow($conn, $_GET['id']);
    } else if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    	$fields = $_POST;
    } else {
    	echo '<h4 class="alert">No todo id provided. Can not update.</h4>';
    	echo '<a href="todos.php">Return to todo list</a>';
    	exit();
    }

    // Check if form has been submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    	if (empty($_POST['Description'])) {
    		echo '<h4 class="alert">Description can not be empty!</h4>';
    		$row = getSingleRow($conn, $_POST['id']);
    		$fields = $row;
    	} else {
    		$fields = $_POST;
    		echo updateRow($conn, $fields);
    		$update = true;
    	}
    }
    ?>

    <fieldset>
    	<legend>Edit A Todo</legend>
    	<form action="edit_todo.php" method="post">
		<table>
			<tr>
				<td><label>Description of your todo task: </label></td>
	            <td><input type="text" name="Description" 
	            	<?php if (!empty($fields['Description'])) {echo 'value="'.$fields['Description'].'"';}
	            	if ($update) {echo ' disabled';} ?>></td>
	        </tr>
	        <tr>
            <td><label>Status of your task: </label></td>
            <td><select name="Status" <?php if ($update) {echo ' disabled';} ?>>
              <option value="Not started"
                <?php if($fields['Status'] === "Not started") {echo ' selected';} ?>
                >Not started</option>
              <option value="In progress"
                <?php if ($fields['Status'] === "In progress") {echo ' selected';} ?>
                >In progress</option>
              <option value="Complete"
                <?php if ($fields['Status'] === "Complete") {echo ' selected';} ?>
                >Complete</option>
              <option value="Canceled"
                <?php if ($fields['Status'] === "Canceled") {echo ' selected';} ?>
                >Canceled</option>
            </select></td>
          </tr>
          <tr>
            <td><label>Task priority: </label></td>
            <td><select name="Priority" <?php if ($update) {echo ' disabled';} ?>>
              <option value="High"
                <?php if ($fields['Priority'] === "High") {echo ' selected';} ?>
                >High</option>
              <option value="Normal"
                <?php if ($fields['Priority'] === "Normal") {echo ' selected';} ?>
                >Normal</option>
              <option value="Low"
                <?php if ($fields['Priority'] === "Low") {echo ' selected';} ?>
                >Low</option>
            </select></td>
          </tr>
          <tr>
            <td></td>
            <?php echo '<input type="hidden" name="id" value="'.$fields['id'].'">'; ?>
            <td><input type="submit" value="Update" <?php if ($update) {echo ' disabled';} ?>></td>
          </tr>
	    </table>
    	</form>
    </fieldset>
    <?php 
    if ($update) {
		echo '<br /><a href="todos.php">Return to todos list</a>';
    } else {
    	echo '<p><a href="todos.php">Cancel</a></p>';
    }
    $conn->close();
    ?>
</body>
</html>