<?php
header("Content-Type: application/json");
// Include DB Connection
include_once("C:/xampp/htdocs/lab_test_comparision/admin/core/dbconnection.php");


$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['labid'])) {
    $labid = $conn->real_escape_string($data['labid']);

    // Update query to set isActive = 0 instead of deleting
    $sql = "UPDATE labs SET IsActive = 0 WHERE labid = $labid";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Lab marked as inactive successfully"]);
    } else {
        echo json_encode(["message" => "Error updating lab: " . $conn->error]);
    }
} else {
    echo json_encode(["message" => "Invalid input"]);
}

$conn->close();
?>
