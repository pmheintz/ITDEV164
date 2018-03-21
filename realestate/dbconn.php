<?php
// Database connection attributes
DEFINE('DB_USER', 'phpheintzpm');
DEFINE('DB_PASSWORD', '1154112');
DEFINE('DB_HOST', 'mca.matc.edu');
DEFINE('DB_NAME', 'phpheintzpm');
DEFINE('CHARSET', 'utf8mb4');

// Set data source name
$dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.CHARSET;
// Set additional options
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Establish connection
try {
	$pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $opt);
}
catch (PDOException $e) {
    echo 'Connection failed: '.$e->getMessage();
}


// Function to ensure html special characters are properly displayed
function hsc($str) { return htmlspecialchars($str); }