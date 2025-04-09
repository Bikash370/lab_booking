<?php
header("Content-Type: application/json");
include __DIR__ . "/dbconnection.php"; // Include the database connection

// Query to get lab details
$sql = "SELECT LabName, CreationTime, CreatorUsedId, IsActive, labid FROM labmaster";
$result = $conn->query($sql);

$labs = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $labs[] = $row;
    }
    echo json_encode($labs);
} else {
    echo json_encode(["error" => "Query failed: " . $conn->error]);
}

$conn->close();
?>
