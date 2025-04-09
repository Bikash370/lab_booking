<?php
header("Content-Type: application/json");
include __DIR__ . "/dbconnection.php"; // Include the database connection

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['tests'])) {
    echo json_encode(["success" => false, "message" => "Invalid request data"]);
    exit;
}

$patientName = $data['patientName'];
$patientAge = intval($data['patientAge']); // Convert Age to Integer
$patientSex = $data['patientSex'];
$patientMobile = $data['patientMobile'];
$tests = $data['tests'];
$isConform = isset($data['IsConform']) ? (bool) $data['IsConform'] : false; // Convert to boolean

// Validate Mobile Number (10 digits)
if (!preg_match('/^\d{10}$/', $patientMobile)) {
    echo json_encode(["success" => false, "message" => "Invalid mobile number"]);
    exit;
}

$conn->begin_transaction(); // Start a transaction

try {
    // Function to generate a unique 6-digit booking ID
    function generateBookingID($conn) {
        do {
            $bookingID = strval(rand(100000, 999999)); // Generate a 6-digit random number
            $query = $conn->prepare("SELECT COUNT(*) FROM bookings WHERE booking_id = ?");
            $query->bind_param("s", $bookingID);
            $query->execute();
            $query->bind_result($count);
            $query->fetch();
            $query->close();
        } while ($count > 0); // Repeat if the ID already exists

        return $bookingID;
    }

    // Generate booking ID once for all tests in this booking
    $bookingID = generateBookingID($conn);

    $stmt = $conn->prepare("INSERT INTO bookings (booking_id, PatientName, Age, Sex, Mobile, TestName, LabName, OriginalPrice, Discount, FinalPrice, BookingTime, IsConform) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)");

    if (!$stmt) {
        throw new Exception("Prepare statement failed: " . $conn->error);
    }

    foreach ($tests as $test) {
        $testName = $test['testname'] ?? ''; 
        $labName = $test['LabName'] ?? '';      
        $originalPrice = floatval($test['fullamount'] ?? 0);
        $discount = floatval($test['discount'] ?? 0);
        $finalPrice = floatval($test['currentamount'] ?? 0);

        $stmt->bind_param("ssissssdddi", $bookingID, $patientName, $patientAge, $patientSex, $patientMobile, $testName, $labName, $originalPrice, $discount, $finalPrice, $isConform);
        $stmt->execute();
    }
    $stmt->close();

    $conn->commit();

    echo json_encode(["success" => true, "message" => "Appointment booked successfully", "booking_id" => $bookingID]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["success" => false, "message" => "Failed to book appointment", "error" => $e->getMessage()]);
}

$conn->close();
?>
