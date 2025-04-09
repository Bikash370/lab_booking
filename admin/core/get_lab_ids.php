<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
include __DIR__ . "/dbconnection.php"; // Include the database connection

// Fetch Unique Lab Names from `labmaster` Table
$sql = "SELECT DISTINCT labName FROM labmaster ORDER BY labid";
$result = $conn->query($sql);

$labNames = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $labNames[] = $row["labName"];
    }
}

// Set correct JSON header
header('Content-Type: application/json');
echo json_encode(["status" => "success", "data" => $labNames]);

$conn->close();
?>
