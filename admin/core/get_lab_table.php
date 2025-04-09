<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include_once __DIR__ . "/dbconnection.php"; // Adjust the path as needed

// Query to get all data from `labmaster`
$sql = "SELECT * FROM labmaster ORDER BY labid";
$result = $conn->query($sql);

$labList = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $labList[] = $row;
    }
}

// Set correct JSON header
header('Content-Type: application/json');
echo json_encode(["status" => "success", "data" => $labList]);

$conn->close();
?>
