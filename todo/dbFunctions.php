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

// Function to display all todos in a table
function getAllTodos($conn) {
  // Begin base SQL statement
  $sql = 'SELECT * FROM todo';

  // Get sorting parameters if any
  if (isset($_GET['sort'])) { $sort = $_GET['sort']; } else { $sort = 'id'; }
  if (isset($_GET['direction'])) { $direction = $_GET['direction']; } else { $direction = 'ASC'; }

  // Check to ensure sort and dirction are acceptable parameters (prevent injection)
  if (!in_array($sort, ['id', 'Description', 'Status', 'Priority'])) { $sort = 'id'; }
  if (!in_array($direction, ['ASC', 'DESC'])) { $direction = 'ASC'; }

  // Append sort parameters to SQL
  if ($sort === 'id' || $sort === 'Description') {
    $sql .= ' ORDER BY '.$sort.' '.$direction;
  } else if ($sort === 'Status') {
    $sql .= ' ORDER BY FIELD(`Status`, "Not started", "In progress", "Complete", "Canceled")'.$direction;
  } else if ($sort === 'Priority') {
    $sql .= ' ORDER BY FIELD(Priority, "High", "Normal", "Low")'.$direction;
  }

  // Execute query
  if (!$result = $conn->query($sql)) {
    echo '<h4 class="alert">There was an error with the query executing on the database!</h4>';
    return;
  }

  // Set sorting direction (ascending/descending)
  if ($direction === 'DESC') { $direction = 'ASC'; } else { $direction = 'DESC'; }

  // Display table
  if ($result->num_rows > 0) {
    echo '<div class="resultSet">'.PHP_EOL.'<table>'.PHP_EOL;
    echo '<tr>'.PHP_EOL;
    echo '<th><a href="todos.php?sort=id&direction=';
    if ($sort !== 'id') { echo 'ASC'; } else { echo $direction; }
    echo '">ID</a></th>'.PHP_EOL;
    echo '<th><a href="todos.php?sort=Description&direction=';
    if ($sort !== 'Description') { echo 'ASC'; } else { echo $direction; }
    echo '">Description</a></th>'.PHP_EOL;
    echo '<th><a href="todos.php?sort=Status&direction=';
    if ($sort !== 'Status') { echo 'ASC'; } else { echo $direction; }
    echo '">Status</a></th>'.PHP_EOL;
    echo '<th><a href="todos.php?sort=Priority&direction=';
    if ($sort !== 'Priority') { echo 'ASC'; } else { echo $direction; }
    echo '">Priority</a></th>'.PHP_EOL.'</tr>'.PHP_EOL;
    // Insert data into table
    while ($row = $result->fetch_assoc()) {
      echo '<tr>'.PHP_EOL.'<td>'.$row['id'].'</td><td>'.$row['Description'].'</td><td>'
            .$row['Status'].'</td><td>'.$row['Priority'].'</td>'.PHP_EOL.'</tr>'.PHP_EOL;
    }
    echo '</table>'.PHP_EOL.'</div>'.PHP_EOL;
  } else {
    // No results were found in the database
    echo '<h4 class="alert">No results returned</h4>';
  }
}
?>