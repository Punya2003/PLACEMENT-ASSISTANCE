<?php
// db_connect.php

$servername = "localhost";
$username = "prajeeth"; // Replace with your database username
$password = "Prajeeth29"; // Replace with your database password
$dbname = "placementassistance";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
