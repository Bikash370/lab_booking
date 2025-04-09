
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/styleS.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li class="tab-link active" data-tab="dashboard"><i class="fas fa-home"></i> Dashboard</li>
            <li class="tab-link" data-tab="labs"><i class="fas fa-flask"></i> Labs</li>
            <li class="tab-link" data-tab="appointments"><i class="fas fa-calendar-alt"></i> Appointments</li>
            <li class="tab-link" data-tab="add-test"><i class="fas fa-vial"></i> Add Test</li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h2>Dashboard</h2>
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span>Admin</span>
            </div>
        </header>

        <!-- Dashboard Tab -->
        <section id="dashboard" class="tab-content active">
            <div class="dashboard-cards">
                <div class="card">
                    <i class="fas fa-flask"></i>
                    <h3>Total Labs</h3>
                    <p>10</p>
                </div>
                <div class="card">
                    <i class="fas fa-calendar-alt"></i>
                    <h3>Appointments</h3>
                    <p>25</p>
                </div>
                <div class="card">
                    <i class="fas fa-dollar-sign"></i>
                    <h3>Revenue</h3>
                    <p>$12,000</p>
                </div>
            </div>
        </section>

        <!-- Labs Tab -->
        <section id="labs" class="tab-content">
   
    <button class="add-lab-btn" onclick="openModal('labModal')">+ Add Lab</button>
    
    
    <table>
    <thead>
        <tr>
           
            <th>Lab ID</th>
            <th>Lab Name</th>
            <th>Creation Time</th>
           
           
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="lab-table-body"></tbody>
</table>

</section>


        <!-- Appointments Tab -->
        <?php
$servername = "localhost"; // Change this if needed
$username = "root"; // Database username
$password = ""; // Database password
$dbname = "compare_lab_test"; // Change to your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Approve button click
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["approve"])) {
    $booking_id = $_POST["booking_id"];
    $update_sql = "UPDATE bookings SET IsConform = 1 WHERE booking_id = '$booking_id'";

    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('Booking approved!'); window.location.href='index.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Fetch pending bookings (IsConform = 0)
$sql_pending = "SELECT DISTINCT booking_id, PatientName, Age, Sex, Mobile, BookingTime 
                FROM bookings WHERE IsConform = 0";
$result_pending = $conn->query($sql_pending);

// Fetch approved bookings (IsConform = 1)
$sql_approved = "SELECT DISTINCT booking_id, PatientName, Age, Sex, Mobile, BookingTime 
                 FROM bookings WHERE IsConform = 1";
$result_approved = $conn->query($sql_approved);
?>

<!-- Appointments Section -->
<section id="appointments" class="tab-content">
    <h3>Pending Appointments</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Patient Name</th>
                <th>Booking Time</th>
                <th>Age</th>
                <th>Sex</th>
                <th>Mobile</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_pending->num_rows > 0) {
                while ($row = $result_pending->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['PatientName']}</td>
                            <td>{$row['BookingTime']}</td>
                            <td>{$row['Age']}</td>
                            <td>{$row['Sex']}</td>
                            <td>{$row['Mobile']}</td>
                            <td>
                                <button onclick=\"viewDetails('{$row['booking_id']}')\">View Details</button>
                                <form method='POST' style='display:inline;'>
                                    <input type='hidden' name='booking_id' value='{$row['booking_id']}'>
                                    <button type='submit' name='approve'>Approve</button>
                                </form>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No pending appointments</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <h3 style="margin-top:22px">Approved Appointments</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Patient Name</th>
                <th>Booking Time</th>
                <th>Age</th>
                <th>Sex</th>
                <th>Mobile</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_approved->num_rows > 0) {
                while ($row = $result_approved->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['PatientName']}</td>
                            <td>{$row['BookingTime']}</td>
                            <td>{$row['Age']}</td>
                            <td>{$row['Sex']}</td>
                            <td>{$row['Mobile']}</td>
                            <td>
                                <button onclick=\"viewDetails('{$row['booking_id']}')\">View Details</button>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No approved appointments</td></tr>";
            }
            ?>
        </tbody>
    </table>
</section>

<?php

?>

<!-- Modal for showing test details -->
<div id="detailsModal" style="display:none; position:fixed; top:10%; left:50%; transform:translate(-50%, 0); background:white; padding:20px; border-radius:10px; box-shadow: 0px 4px 10px rgba(0,0,0,0.1);">
    <h3>Test Details</h3>
    <p><strong>Booking ID:</strong> <span id="modalBookingID"></span></p>
    <table border="1">
        <thead>
            <tr>
                <th>Test Name</th>
                <th>Lab Name</th>
                <th>Original Price</th>
                <th>Discount</th>
                <th>Final Price</th>
            </tr>
        </thead>
        <tbody id="modalTestDetails"></tbody>
    </table>
    <br>
    <button onclick="document.getElementById('detailsModal').style.display='none'">Close</button>

</div>


        <!-- Add Test Tab -->
        <section id="add-test" class="tab-content">
        <h3>Manage Tests</h3>

<!-- Filter Section -->
<div class="filter-container" style="display: flex; align-items: center; justify-content: space-between; margin-top: 20px;">
    <div style="display: flex; align-items: center; gap: 10px;">
        <label for="filter-labid">Filter by Lab:</label>
        <select id="filter-labid" style="padding: 5px; border: 1px solid #ccc; border-radius: 4px;">
            <option value="">Select Lab</option> <!-- Default option -->
        </select>
        <button onclick="filterTests()" 
            style="padding: 6px 12px; background-color: #28a745; color: white; border: none; cursor: pointer; border-radius: 4px;">
            Search
        </button>
    </div>

    <!-- "+ Add Test" Button -->
    <button class="add-test-btn" onclick="openModal('testModal')" 
        style="padding: 8px 12px; background-color: #007bff; color: white; border: none; cursor: pointer; border-radius: 4px;">
        + Add Test
    </button>
</div>




    <table>
        <thead>
            <tr>
                <th>Lab Name</th>
                <th>Test Name</th>
                <th>Full Amount</th>
                <th>Current Amount</th>
                <th>Discount (%)</th>
                <th>Discount (Amount)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="test-table-body"></tbody>
    </table>
</section>



<!-- Edit Test Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeEditModal()">&times;</span>
        <h2>Edit Test Discount</h2>
        
        <form id="editTestForm">
            <label for="editLabName">Lab Name:</label>
            <input type="text" id="editLabName" readonly>

            <label for="editTestName">Test Name:</label>
            <input type="text" id="editTestName" readonly>

            <label for="editFullAmount">Full Amount:</label>
            <input type="text" id="editFullAmount" readonly>

            <label for="editDiscount">Discount %:</label>
            <input type="number" id="editDiscount" oninput="calculateUpdatedAmount()" required>

            <label for="editCurrentAmount">Current Amount:</label>
            <input type="text" id="editCurrentAmount" readonly>

            <label for="editDiscountAmount">Discount Amount:</label>
            <input type="text" id="editDiscountAmount" readonly>

            <button type="button" onclick="saveTestChanges()">Save</button>
        </form>
    </div>
</div>



       <!-- Lab Modal Form -->
<div id="labModal" class="modal">
    <div class="modal-content">
        <h3>Add New Lab</h3>

        <label for="labID">Lab ID</label>
        <input type="number" id="labID" placeholder="Enter Lab ID" required>

        <label for="labName">Lab Name</label>
        <input type="text" id="labName" placeholder="Enter Lab Name" required>

        <button class="save-btn" onclick="saveLab()">Save Lab</button>
        <button class="close-btn" onclick="closeModal('labModal')">Close</button>
    </div>
</div>




        <!-- Test Modal Form -->
        <div id="testModal" class="modal" style="display:none;">
    <div class="modal-content">
        <h3>Add New Test</h3>
        
        <!-- Lab ID Dropdown -->
      <!-- Lab ID Dropdown (Dynamically Populated) -->
<select id="labId" required>
    <option value="">Select Lab ID</option>
</select>
        
        <input type="text" id="testName" placeholder="Test Name" required>

        <!-- Test Type Dropdown -->
        <select id="testType" required>
            <option value="">Select Test Type</option>
            <option value="Blood">Blood</option>
            <option value="Urine">Urine</option>
            <option value="Saliva">Saliva</option>
        </select>

        <!-- Sample Required Dropdown -->
        <select id="sampleRequired" required>
            <option value="">Sample Required?</option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
        </select>

        <!-- Fasting Required Dropdown -->
        <select id="fastingRequired" required>
            <option value="">Fasting Required?</option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
        </select>

        <input type="text" id="resultTime" placeholder="Result Time in hours">
        <textarea id="description" placeholder="Description"></textarea>

        <input type="number" id="fullAmount" placeholder="Full Amount" required oninput="calculateDiscount()">
        <input type="number" id="discountPercent" placeholder="Discount (%)" oninput="calculateDiscount()">
        <input type="text" id="discountAmount" placeholder="Discount in Amount" readonly>
        <input type="text" id="currentAmount" placeholder="Current Amount" readonly>

        <button class="save-btn" onclick="saveTest()">Save Test</button>
        <button class="close-btn" onclick="closeModal('testModal')">Close</button>
    </div>
</div>


    </div>

    <script src="js/script.js"></script>

</body>
</html>
