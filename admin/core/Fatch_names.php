<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // Allow access from any domain
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Origin: *"); // Or a specific domain instead of '*'
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");



// Database connection
$host = "localhost"; // Change if using a remote DB
$user = "root"; // Database username
$password = ""; // Database password (leave empty if no password)
$database = "lab_test_comparision"; // Your database name

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// SQL query to fetch labs
$sql = "SELECT  LabName FROM labmaster"; // Adjust table name if needed
$result = $conn->query($sql);

$labs = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $labs[] = [
           
            "LabName" => $row["LabName"]
          
        ];
    }
}

// Return JSON response
echo json_encode($labs);

$conn->close();
?>
