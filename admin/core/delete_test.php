<?php
header("Content-Type: application/json");
include __DIR__ . "/dbconnection.php"; // Include database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $testid = isset($_POST["testid"]) ? intval($_POST["testid"]) : 0;

    if ($testid > 0) {
        $sql = "UPDATE testmaster SET isActive = 0 WHERE testid = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $testid);
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode(["status" => "success", "message" => "Test deleted successfully."]);
                } else {
                    echo json_encode(["status" => "error", "message" => "No rows affected. Test ID might not exist."]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Execution failed: " . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(["status" => "error", "message" => "Statement preparation failed: " . $conn->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid test ID."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

$conn->close();
?>
