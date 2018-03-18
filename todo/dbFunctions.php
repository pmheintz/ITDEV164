<?php
/*
/	Function to add a row into the database
/	$conn - connection to the database
/	$params - array of fields to add to the database
/	returns string with result of INSERT INTO
*/
function addRow($conn, $params) {
	// Return string
	$result = '';

	// SQL statement
	$sql = "INSERT INTO todo (Description, `Status`, Priority) VALUES (?, ?, ?)";

	// Prepare statement
	$stmt = $conn->prepare($sql);

	// Check if statement is ok
	if ($stmt === false) {
		// There's an error in the SQL prepared statement
		$result = '<h4 class="alert">** ERROR IN SQL INSERT STATEMENT. Please file bug report. **</h4>';
	} else {
		// Statement is ok, bind parameters
		$stmt->bind_param('sss', $params['Description'], $params['Status'], $params['Priority']);
		// Attempt to execute statement
		if ($stmt->execute()) {
			// Statement was successful, check affected rows
			if ($stmt->affected_rows > 0) {
				$result = '<h4 class="primary">Success! '.$stmt->affected_rows.' row added.</h4>';
			} else if ($stmt->affected_rows === 0) {
				$result = '<h4 class="primary">Success, but no rows changed.</h4>';
			}
		} else {
			// SQL statement failed to execute
			$result = '<h4 class="alert">Query failed with error message: '.$stmt->error.'</h4>';
		}
	}

	// Close statement
	$stmt->close();

	// Return result
	return $result;
}

/*
/	Function to get number of rows in database
/	$conn - connection to the database
/	returns integer number of rows
*/
function getNumRows($conn) {
	$totalRows = 0;
	if ($result = $conn->query("SELECT id FROM todo")) {
	    $totalRows = $result->num_rows;

		// Close query
		$result->close();
	}

	return $totalRows;
}

/*
/	Function to get the number of pages for pagination
/	$conn - connection to the database
/	$rowsPerPage - rows per page
/	returns integer number of pages, 0 if there's no rows
*/
function getNumPages($conn, $rowsPerPage) {
	// Get number of rows in database
	$result = $conn->query('SELECT id FROM todo');
	$totalRows = $result->num_rows;

	// Check if there are any rows
	if ($totalRows !== 0) {
		// If there's more rows then rows per page, calculate number of pages
		if (is_numeric($rowsPerPage) && $totalRows > $rowsPerPage) {
			$pages = ceil($totalRows/$rowsPerPage);
		} else {
			$pages = 1;
		}
	} else {
		$pages = 0; // No rows, no pages
	}

	return $pages;
}

/*
/	Function to get all rows from todo table
/	$conn - connection to the database
/	$orderBy - column to order by
/	$direction - ascending(ASC) or decending(DSC)
/	returns a result object or 0 if no results found
*/
function getAllTodos($conn, $orderBy, $direction) {
	// Base sql statement
	$sql = "SELECT id, Description, `Status`, Priority FROM todo ORDER BY ";

	// Append sorting parameters
	if ($orderBy === 'id' || $orderBy === 'Description') {
		$sql .= "$orderBy $direction";
	} else if ($orderBy === 'Status') {
	    $sql .= "FIELD(`Status`, \"Not started\", \"In progress\", \"Complete\", \"Canceled\") $direction";
	} else if ($orderBy === 'Priority') {
	    $sql .= "FIELD(Priority, \"High\", \"Normal\", \"Low\") $direction";
	}

	// Prepare statement
	$stmt = $conn->prepare($sql);

	// Check if statement is ok
	if ($stmt === false) {
		// There's an error in the SQL prepared statement
		$result = '<h4 class="alert">** ERROR IN SQL SELECT STATEMENT. Please file bug report. **</h4>';
	} else {
		if ($stmt->execute()) {
			// Statement executed successfully
			$result = $stmt->get_result();
		} else {
			// SQL statement failed to execute
			$result = '<h4 class="alert">Query failed with error message: '.$stmt->error.'</h4>';
		}
	}

	// Close statement and return results
	$stmt->close();
	return $result;
}

/*
/	Function to get all rows within a range
/	$conn - connection to the database
/	$orderBy - column to order by
/	$direction - ascending(ASC) or decending(DSC)
/	$start - the starting point for the rows to get
/	$display - how many rows to get
/	returns a result object or 0 if no results found
*/
function getTodosWithRange($conn, $orderBy, $direction, $start, $display) {
	// Base SQL query
	$sql = 'SELECT id, Description, `Status`, Priority FROM todo';

	// Append parameters
	if ($orderBy === 'id' || $orderBy === 'Description') {
		$sql .= ' ORDER BY '.$orderBy.' '.$direction.' LIMIT '.$start.', '.$display;
	} else if ($orderBy === 'Status') {
	    $sql .= ' ORDER BY FIELD(`Status`, "Not started", "In progress", "Complete", "Canceled")'
	            .$direction.' LIMIT '.$start.', '.$display;
	} else if ($orderBy === 'Priority') {
	    $sql .= ' ORDER BY FIELD(Priority, "High", "Normal", "Low")'.$direction
	            .' LIMIT '.$start.', '.$display;
	}

	// Prepare statement
	$stmt = $conn->prepare($sql);

	// Check if statement is ok
	if ($stmt === false) {
		// There's an error in the SQL prepared statement
		$result = '<h4 class="alert">** ERROR IN SQL SELECT STATEMENT. Please file bug report. **</h4>';
	} else {
		if ($stmt->execute()) {
			// Statement executed successfully
			$result = $stmt->get_result();
		} else {
			// SQL statement failed to execute
			$result = '<h4 class="alert">Query failed with error message: '.$stmt->error.'</h4>';
		}
	}

	// Close statement and return results
	$stmt->close();
	return $result;
}

/*
/ Function to get single row from database
/ $conn - connection to the database
/ $id - primary key for row to get
/ returns the row that was queried
*/
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

/*
/ Function to update a row in the table
/ $conn - connection to the database
/ $fields - associative array containing fields to update
/ returns string displaying results from update
*/
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

/*
/ Function to delete a row from the table
/ $conn - connection to database
/ $id - id of row to delete
/ returns string displaying results from delete
*/
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