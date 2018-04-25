<?php
session_start();
require_once('dbconn.php');
echo createPlaceholder(1, $pdo);
?>