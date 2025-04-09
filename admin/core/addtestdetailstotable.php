<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . "/dbconnection.php";

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid data received"]);
    exit();
}

// Extract LabName from input
$labname = isset($data['labname']) ? trim($data['labname']) : "";

if (empty($labname)) {
    echo json_encode(["status" => "error", "message" => "Lab name is required"]);
    exit();
}

// Retrieve labid from labmaster table based on LabName
$labQuery = "SELECT labid FROM labmaster WHERE LabName = ?";
$stmt = $conn->prepare($labQuery);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "SQL Prepare failed: " . $conn->error]);
    exit();
}

$stmt->bind_param("s", $labname);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo json_encode(["status" => "error", "message" => "Lab name not found"]);
    exit();
}

$labid = $row['labid']; // Retrieved Lab ID

$stmt->close();

// Generate Test ID using labid prefix
$labPrefix = strtoupper(substr($labid, 0, 3)); // Prefix for testid

function generateTestID($conn, $labPrefix) {
    $query = "SELECT testid FROM testmaster WHERE testid LIKE ? ORDER BY testid DESC LIMIT 1";
    $likeParam = $labPrefix . '%';  // No underscore in LIKE condition
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die(json_encode(["status" => "error", "message" => "SQL Prepare failed: " . $conn->error]));
    }
    
    $stmt->bind_param("s", $likeParam);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        preg_match('/(\d+)$/', $row['testid'], $matches); // Extract last numeric part
        $numericPart = isset($matches[1]) ? (int)$matches[1] + 1 : 1;
    } else {
        $numericPart = 1; // First entry
    }

    $stmt->close();
    return $labPrefix . str_pad($numericPart, 3, '0', STR_PAD_LEFT); // No underscore
}

// Generate new Test ID
$testid = generateTestID($conn, $labPrefix);

// Validate and sanitize input data
$testname = isset($data['testname']) ? trim($data['testname']) : "";
$testtype = isset($data['testtype']) ? trim($data['testtype']) : "";
$samplerequired = isset($data['samplerequired']) ? trim($data['samplerequired']) : "No";
$fastingrequired = isset($data['fastingrequired']) ? trim($data['fastingrequired']) : "No";
$resulttime = isset($data['resulttime']) ? trim($data['resulttime']) : "";
$description = isset($data['description']) ? trim($data['description']) : "";
$fullamount = isset($data['fullamount']) ? (float)$data['fullamount'] : 0.0;
$currentamount = isset($data['currentamount']) ? (float)$data['currentamount'] : 0.0;
$discount = isset($data['discount']) ? (float)$data['discount'] : 0.0;
$discountinamount = isset($data['discountinamount']) ? (float)$data['discountinamount'] : 0.0;

// Prepare the SQL statement
$sql = "INSERT INTO testmaster (testid, labid, LabName, testname, testtype, samplerequired, fastingrequired, resulttime, description, fullamount, currentamount, discount, discountinamount) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "SQL Prepare failed: " . $conn->error]);
    exit();
}

// Bind parameters correctly
$stmt->bind_param("ssssssssssddd", $testid, $labid, $labname, $testname, $testtype, $samplerequired, $fastingrequired, $resulttime, $description, $fullamount, $currentamount, $discount, $discountinamount);

// Execute and check result
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Test saved successfully!", "testid" => $testid]);
} else {
    echo json_encode(["status" => "error", "message" => "Error saving test: " . $stmt->error]);
}

// Close resources
$stmt->close();
$conn->close();
?>
