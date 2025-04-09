<?php
header("Content-Type: application/json");
include __DIR__ . "/dbconnection.php"; // Include the database connection

// Query to fetch active test details in ascending order of testid
$sql = "SELECT * FROM testmaster WHERE isActive = 1 ORDER BY testid ASC";

$result = $conn->query($sql);

$tests = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tests[] = $row;
    }
}

// Return JSON response
echo json_encode($tests);

$conn->close();
?>
