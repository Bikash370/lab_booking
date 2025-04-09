<?php
header("Content-Type: application/json");

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "compare_lab_test"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

if (!isset($_GET['booking_id'])) {
    die(json_encode(["error" => "Missing booking_id"]));
}

$booking_id = $_GET['booking_id'];

$sql = "SELECT TestName, LabName, OriginalPrice, Discount, FinalPrice FROM bookings WHERE booking_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $booking_id);
$stmt->execute();
$result = $stmt->get_result();

$tests = [];
while ($row = $result->fetch_assoc()) {
    $tests[] = $row;
}

echo json_encode($tests);
?>
