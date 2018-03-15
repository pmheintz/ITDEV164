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
  /**  Pagination section **/
    // Set the max rows to display per page
    if (!isset($_GET['rows']) || $_GET['rows'] === 'all') {
      $display = 1000000000; // High number to attempt to return all
    } else {
      if (!is_numeric($_GET['rows'])) {
        echo '<h4 class="alert">** Error! Unacceptable value for rows **';
        exit();
      } else {
        $display = $_GET['rows'];
      }
    }

    // Determine the number of pages
    if (isset($_GET['pages']) && is_numeric($_GET['pages'])) {
      $pages = $_GET['pages'];
    } else {
      $result = $conn->query('SELECT id FROM todo');
      $records = $result->num_rows;

      // Determine number of $pages
      if ($records > $display) {
        $pages = ceil($records/$display);
      } else {
        $pages = 1;
      }
    }

    // Determine starting point for displayed rows
    if (isset($_GET['start']) && is_numeric($_GET['start'])) {
      $start = $_GET['start'];
    } else {
      $start = 0;
    }
  /** End of pagination section **/

  // Begin base SQL statement
  $sql = 'SELECT * FROM todo';

  // Get sorting parameters if any
  $sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'id';
  $direction = (isset($_GET['direction'])) ? $_GET['direction'] : 'ASC';

  // Check to ensure sort and dirction are acceptable parameters (prevent injection)
  if (!in_array($sort, ['id', 'Description', 'Status', 'Priority'])) { $sort = 'id'; }
  if (!in_array($direction, ['ASC', 'DESC'])) { $direction = 'ASC'; }

  // Append sort parameters to SQL
  if ($sort === 'id' || $sort === 'Description') {
    $sql .= ' ORDER BY '.$sort.' '.$direction.' LIMIT '.$start.', '.$display;
  } else if ($sort === 'Status') {
    $sql .= ' ORDER BY FIELD(`Status`, "Not started", "In progress", "Complete", "Canceled")'
            .$direction.' LIMIT '.$start.', '.$display;
  } else if ($sort === 'Priority') {
    $sql .= ' ORDER BY FIELD(Priority, "High", "Normal", "Low")'.$direction
            .' LIMIT '.$start.', '.$display;
  }

  // Execute query
  if (!$result = $conn->query($sql)) {
    echo '<h4 class="alert">There was an error with the query executing on the database!</h4>';
    return;
  }

  // Set sorting direction (ascending/descending)
  $direction = ($direction === 'DESC') ? 'ASC' : 'DESC';

  // Display table
  if ($result->num_rows > 0) {
    echo '<div class="resultSet">'.PHP_EOL.'<table>'.PHP_EOL;
    echo '<tr>'.PHP_EOL;
    echo '<th></th><th><a href="todos.php?rows='.$display.'&sort=id&direction=';
    if ($sort !== 'id') { echo 'ASC'; } else { echo $direction; }
    echo '">ID</a></th>'.PHP_EOL;
    echo '<th><a href="todos.php?rows='.$display.'&sort=Description&direction=';
    if ($sort !== 'Description') { echo 'ASC'; } else { echo $direction; }
    echo '">Description</a></th>'.PHP_EOL;
    echo '<th><a href="todos.php?rows='.$display.'&sort=Status&direction=';
    if ($sort !== 'Status') { echo 'ASC'; } else { echo $direction; }
    echo '">Status</a></th>'.PHP_EOL;
    echo '<th><a href="todos.php?rows='.$display.'&sort=Priority&direction=';
    if ($sort !== 'Priority') { echo 'ASC'; } else { echo $direction; }
    echo '">Priority</a></th>'.PHP_EOL.'</tr>'.PHP_EOL;
    // Insert data into table
    while ($row = $result->fetch_assoc()) {
      echo '<tr>'.PHP_EOL;
      echo '<td><a href="edit_todo.php?id='.$row['id'].'">Update</a> &nbsp; <a href="delete_todo.php?id='.$row['id'].'">Delete</a></td>'.PHP_EOL;
      echo '<td>'.$row['id'].'</td><td>'.$row['Description'].'</td><td>'
            .$row['Status'].'</td><td>'.$row['Priority'].'</td>'.PHP_EOL.'</tr>'.PHP_EOL;
    }
    echo '</table>'.PHP_EOL.'</div>'.PHP_EOL;
  } else {
    // No results were found in the database
    echo '<h4 class="alert">No results returned</h4>';
  }

  /** Generated html for Pagination **/
  if ($pages > 1) {
    // Keep sorting direction
    if ($direction === 'ASC') {$direction = 'DESC';} else {$direction = 'ASC';}
    // Get the current page
    $currentPage = ($start/$display) + 1;
    echo '<div class="pagination">'.PHP_EOL.'<ul>'.PHP_EOL;

    // Set the previous button
    if ($currentPage != 1) {
      echo '<li><a href="todos.php?rows='.$display.'&start='.($start - $display).'&pages='.$pages
            .'&sort='.$sort.'&direction='.$direction.'">Previous </a></li>'.PHP_EOL;
    } else {
      echo '<li><span style="color: gray;">Previous </span></li>'.PHP_EOL;
    }

    // Set the page numbers
    for ($i = 1; $i <= $pages; $i++) {
      if ($i != $currentPage) {
        echo '<li><a href="todos.php?rows='.$display.'&start='.($display * ($i - 1)).'&pages='.$pages
              .'&sort='.$sort.'&direction='.$direction.'">'. $i .'</a></li>'.PHP_EOL;
      } else {
        echo '<li class="current">'.$i.'</li>'.PHP_EOL;
      }
    }

    // Set the next button
    if ($currentPage != $pages) {
      echo '<li><a href="todos.php?rows='.$display.'&start='.($start + $display).'&pages='.$pages
            .'&sort='.$sort.'&direction='.$direction.'"> Next</a></li>'.PHP_EOL;
    } else {
      echo '<li><span style="color: gray;"> Next</span></li>'.PHP_EOL;
    }

    echo '</ul>'.PHP_EOL.'</div>';
  }
}

// Function to get single row from database
function getSingleRow($conn, $id) {
  // Result of query
  $result = '';

  // SQL query
  $sql = "SELECT * FROM todo WHERE id=?";

  // Prepare statement
  $stmt = $conn->prepare($sql);

  // Bind parameters and execute
  if ($stmt === false) {
    $result = '<span class="alert">** ERROR IN SQL SELECT STATEMENT. Please file bug report. **</span>';
  } else {
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
      // Statement failed to execute
      $result = '<h4 class="alert">Query failed with error message: '.$stmt->error.'</h4>';
    } else {
      // Assign result
      $row = $stmt->get_result();
      $result = $row->fetch_assoc();
    }
  }
  // Close statement and return result
  $stmt->close();
  return $result;
}

// Function to update a row in the table
function updateRow($conn, $fields) {
  // Result of the query
  $result = '';

  // SQL query
  $sql = "UPDATE todo SET Description=?, `Status`=?, Priority=? WHERE id=?";

  // Prepare statement
  $stmt = $conn->prepare($sql);

  // Bind parameters and execute
  if ($stmt === false) {
    $result = '<span class="alert">** ERROR IN SQL UPDATE STATEMENT. Please file bug report. **</span>';
  } else {
    $stmt->bind_param('sssi', $fields['Description'], $fields['Status'], $fields['Priority'], $fields['id']);
    if (!$stmt->execute()) {
      // Statement failed to execute
      $result = '<h4 class="alert">Query failed with error message: '.$stmt->error.'</h4>';
    } else {
      if ($stmt->affected_rows > 0) {
        $result = '<h4 class="primary">Success! '.$stmt->affected_rows.' row(s) updated.</h4>';
      } else {
        $result = '<h4 class="primary">Success! However, no rows were changed.</h4>';
      }
    }
  }
  // Close statement and return result
  $stmt->close();
  return $result;
}

// Function to delete a row from the table
function deleteRow($conn, $id) {
  // Result of the query
  $result = '';

  // SQL query
  $sql = "DELETE FROM todo WHERE id=?";

  // Prepare statement
  $stmt = $conn->prepare($sql);

  // Bind parameters and execute
  if ($stmt === false) {
    $result = '<span class="alert">** ERROR IN SQL DELETE STATEMENT. Please file bug report. **</span>';
  } else {
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
      // Statement failed to execute
      $result = '<h4 class="alert">Query failed with error message: '.$stmt->error.'</h4>';
    } else {
      // Delete was successful
      if ($stmt->affected_rows > 0) {
        $result = '<h4 class="primary">Success! '.$stmt->affected_rows.' row(s) deleted.</h4>';
      } else {
        $result = '<h4 class="primary">Success! However, no rows were changed.</h4>';
      }
    }
  }
  $stmt->close();
  return $result;
}

?>
