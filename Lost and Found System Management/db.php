<?php
// Connect to the Lost and Found database

$host = "localhost";
$username = "root";
$password = "";
$database = "lost_and_found_db";

$conn = new mysqli($host, $username, $password, $database);

// Check database connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>