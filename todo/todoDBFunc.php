<?php
// Function to add a todo item to database
function addTodo() {
  global $conn;
  // Prepare sql statement to prevent injection
  $sql = $conn->prepare("INSERT INTO todo (Description, Status, Priority) VALUES (?, ?, ?)");
  // Bind parameters
  $sql->bind_param("sss", $_POST['Description'], $_POST['Status'], $_POST['Priority']);

  // Execute SQL statement
  $sql->execute();

  // Close statement and connection
  $sql->close();
  $conn->close();
}
?>
