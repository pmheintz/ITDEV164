<?php
// Function to add a todo to the Database
function addTodo($conn) {
  // Variable to hold result
  $result = '';

  // SQL statement
  $sql = "INSERT INTO todo (Description, Status, Priority) VALUES (?, ?, ?)";

  // Prepare statement
  $stmt = $conn->prepare($sql);

  // Check to ensure SQL statement is OK, bind paramaters and execute if yes
  if ($stmt === false) {
    $result = '<span class="alert">** ERROR IN SQL INSERT STATEMENT. Please file bug report. **</span>';
  } else {
    $stmt->bind_param('sss',  $_POST['Description'], $_POST['Status'], $_POST['Priority']);
    // Execute SQL statement
    if ($stmt->execute()) {
      // Add affected rows to $result
      if ($stmt->affected_rows > 0) {
        $result = '<h4 class="primary">Success! '.$stmt->affected_rows.' row(s) affected in todo database</h4>';
      } else if ($stmt->affected_rows === 0) {
        $result = '<h4 class="primary">Success, but no rows changed in todo database.</h4>';
      }
      // Add data echo to result
      $result .= '<div class="resultSet">'.PHP_EOL.'<table>';
      $result .= '<tr><th>Description</th><th>Status</th><th>Priority</th></tr>';
      $result .= '<tr><td>'.$_POST['Description'].'</td><td>'.$_POST['Status'].
                 '</td><td>'.$_POST['Priority'].'</td></tr>';
      $result .= '</table>'.PHP_EOL.'</div>';
    } else {
      // SQL statement failed to execute
      $result = '<h4 class="alert">Query failed with error message: '.$stmt->error.'</h4>';
    }
  }
  // Close SQL statement
  if (!$stmt === false) {
    $stmt->close();
  }
  // Return function result
  return $result;
}
?>
