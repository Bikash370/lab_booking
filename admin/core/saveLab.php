<?php
header("Content-Type: application/json");
include __DIR__ . "/dbconnection.php"; // Ensure correct path to dbconnection.php

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid data received"]);
    exit();
}

// Validate and sanitize input data
$labID = isset($data['labID']) ? (int) $data['labID'] : 0;
$labName = isset($data['labName']) ? trim($data['labName']) : "";

if ($labID <= 0 || empty($labName)) {
    echo json_encode(["status" => "error", "message" => "Lab ID and Lab Name are required"]);
    exit();
}

// Prepare SQL statement
$sql = "INSERT INTO LabMaster (labID, LabName, CreationTime, CreatorUsedId, IsActive) 
        VALUES (?, ?, NOW(), ?, 1)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "SQL Prepare failed: " . $conn->error]);
    exit();
}

// Assuming CreatorUsedId is a static ID for now (Change as needed)
$creatorUserId = 1;

// Bind parameters
$stmt->bind_param("isi", $labID, $labName, $creatorUserId);

// Execute and check result
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Lab added successfully!", "labId" => $stmt->insert_id]);
} else {
    echo json_encode(["status" => "error", "message" => "Error adding lab: " . $stmt->error]);
}

// Close resources
$stmt->close();
$conn->close();
?>
