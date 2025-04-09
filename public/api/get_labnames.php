<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // Allow cross-origin requests (adjust for security)
header("Access-Control-Allow-Methods: GET");

// Database connection
$conn = new mysqli("localhost", "root", "", "compare_lab_test");

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Fetch lab names
$sql = "SELECT LabName FROM labmaster ORDER BY LabName ASC";
$result = $conn->query($sql);

$labs = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $labs[] = $row;
    }
}

// Return data as JSON
echo json_encode($labs);

$conn->close();
?>
