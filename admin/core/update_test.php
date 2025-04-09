<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Include DB Connection
include_once("C:/xampp/htdocs/lab_test_comparision/admin/core/dbconnection.php");

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->testid) || !isset($data->discount) || !isset($data->discountinamount) || !isset($data->currentamount)) {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit;
}

$testid = $data->testid;
$discount = $data->discount;
$discountinamount = $data->discountinamount;
$currentamount = $data->currentamount;

// Update query
$sql = "UPDATE testmaster SET discount = ?, discountinamount = ?, currentamount = ? WHERE testid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("dddi", $discount, $discountinamount, $currentamount, $testid);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Test discount updated"]);
} else {
    echo json_encode(["status" => "error", "message" => "Update failed"]);
}

$stmt->close();
$conn->close();
?>
