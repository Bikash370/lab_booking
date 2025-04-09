<?php
header("Content-Type: application/json");
// Include DB Connection
include_once("C:/xampp/htdocs/lab_test_comparision/admin/core/dbconnection.php");

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['labid']) && isset($data['labName'])) {
    $labid = $conn->real_escape_string($data['labid']);
    $labName = $conn->real_escape_string($data['labName']);

    $sql = "UPDATE labs SET LabName='$labName' WHERE labid=$labid";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Lab updated successfully"]);
    } else {
        echo json_encode(["message" => "Error updating lab: " . $conn->error]);
    }
} else {
    echo json_encode(["message" => "Invalid input"]);
}

$conn->close();
?>
