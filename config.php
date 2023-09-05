<?php
$user = 'root';
$password = '';
$database = 'visualdashboard';
$servername='localhost:3306';
$mysqli = new mysqli($servername, $user, $password, $database);

// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}
?>