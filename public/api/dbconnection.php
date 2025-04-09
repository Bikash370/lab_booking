<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "compare_lab_test"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
