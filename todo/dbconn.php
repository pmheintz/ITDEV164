<?php
// For debugging
ini_set('display_errors', 1);
// Database connection attributes
DEFINE('DB_USER', 'phpheintzpm');
DEFINE('DB_PASSWORD', '1154112');
DEFINE('DB_HOST', 'mca.matc.edu');
DEFINE('DB_NAME', 'phpheintzpm');

// Instantiate connection object
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check if connection was made
if ($conn->connect_error) {
  die('Connection failed: '.$conn->connect_error);
}
?>
