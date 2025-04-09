document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll(".tab-link");
    const tabContents = document.querySelectorAll(".tab-content");

    tabs.forEach(tab => {
        tab.addEventListener("click", function () {
            tabs.forEach(t => t.classList.remove("active"));
            tabContents.forEach(tc => tc.classList.remove("active"));

            this.classList.add("active");
            document.getElementById(this.dataset.tab).classList.add("active");
        });
    });
});



// Modal Functions
function openModal(modalId) {
    document.getElementById(modalId).style.display = "flex";
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

// Function to Clear Form Fields
function clearFormFields() {
    document.getElementById("labId").value = "";
    document.getElementById("testName").value = "";
    document.getElementById("testType").value = "";
    document.getElementById("sampleRequired").value = "";
    document.getElementById("fastingRequired").value = "";
    document.getElementById("resultTime").value = "";
    document.getElementById("description").value = "";
    document.getElementById("fullAmount").value = "";
    document.getElementById("discountPercent").value = "";
    document.getElementById("discountAmount").value = "";
    document.getElementById("currentAmount").value = "";
}

// Function to Save Test
function saveTest() {
    let labId = document.getElementById("labId").value.trim();
    let labName = document.getElementById("labId").options[document.getElementById("labId").selectedIndex].text; // Get Lab Name
    let testName = document.getElementById("testName").value.trim();
    let testType = document.getElementById("testType").value.trim();
    let sampleRequired = document.getElementById("sampleRequired").value.trim();
    let fastingRequired = document.getElementById("fastingRequired").value.trim();
    let resultTime = document.getElementById("resultTime").value.trim();
    let description = document.getElementById("description").value.trim();
    let fullAmount = parseFloat(document.getElementById("fullAmount").value) || 0;
    let discountPercent = parseFloat(document.getElementById("discountPercent").value) || 0;

    // Validation
    if (!labId) return toastr.error("Please select a Lab Name.");
    if (!testName) return toastr.error("Please enter the Test Name.");
    if (!testType) return toastr.error("Please select a Test Type.");
    if (!sampleRequired) return toastr.error("Please select whether Sample is required.");
    if (!fastingRequired) return toastr.error("Please select whether Fasting is required.");
    if (!fullAmount || isNaN(fullAmount) || fullAmount <= 0) return toastr.error("Please enter a valid Full Amount.");
    if (discountPercent < 0 || discountPercent > 100) return toastr.warning("Please enter a valid Discount Percentage (0-100).");

    // Calculate Discount
    let discountAmount = (fullAmount * discountPercent) / 100;
    let currentAmount = fullAmount - discountAmount;

    document.getElementById("discountAmount").value = discountAmount.toFixed(2);
    document.getElementById("currentAmount").value = currentAmount.toFixed(2);

    // Prepare Data for Backend
    let testData = {
        labid: labId,         // Send Lab ID
        labname: labName,     // Send Lab Name
        testname: testName,
        testtype: testType,
        samplerequired: sampleRequired,
        fastingrequired: fastingRequired,
        resulttime: resultTime,
        description: description,
        fullamount: fullAmount,
        discount: discountPercent,
        discountinamount: discountAmount,
        currentamount: currentAmount
    };

    // Send Data to Backend
    fetch("http://localhost/lab_test_comparision/admin/core/addtestdetailstotable.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(testData)
    })
    .then(response => response.json())
    .then(data => {
        console.log("API Response:", data);

        if (!data) {
            toastr.error("Server returned an empty response.");
            return;
        }

        if (data.status === "success") {
            toastr.success("Test saved successfully!");
            clearFormFields();
            closeModal("testModal");
        } else {
            toastr.error(data.message || "An error occurred while saving test details.");
        }
    })
    .catch(error => {
        console.error("Error:", error);
        toastr.error("Something went wrong. Please try again.");
    });
}




function saveLab() {
    const labIDInput = document.getElementById("labID");
    const labNameInput = document.getElementById("labName");

    const labID = labIDInput.value.trim();
    const labName = labNameInput.value.trim();

    if (!labID || !labName) {
        toastr.error("Please fill in all fields.", "Error");
        return;
    }

    const labData = {
        labID: labID,
        labName: labName
    };

    fetch("http://localhost/lab_test_comparision/admin/core/saveLab.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(labData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            toastr.success("Lab added successfully!", "Success");

            // Clear input fields
            labIDInput.value = "";
            labNameInput.value = "";

            // Close the modal
            closeModal('labModal');

            // Refresh Lab IDs dropdown
            fetchLabIds();
        } else {
            toastr.error("Failed to add lab: " + data.message, "Error");
        }
    })
    .catch(error => {
        console.error("Error:", error);
        toastr.error("Failed to add lab. Please try again.", "Error");
    });
}



// Function to Calculate Discount
function calculateDiscount() {
    let fullAmount = parseFloat(document.getElementById("fullAmount").value) || 0;
    let discountPercent = parseFloat(document.getElementById("discountPercent").value) || 0;

    let discountAmount = (fullAmount * discountPercent) / 100;
    let currentAmount = fullAmount - discountAmount;

    document.getElementById("discountAmount").value = discountAmount.toFixed(2);
    document.getElementById("currentAmount").value = currentAmount.toFixed(2);
}

// Function to Show Toast Messages
function showToast(message, type) {
    let toastContainer = document.getElementById("toast-container");
    if (!toastContainer) {
        toastContainer = document.createElement("div");
        toastContainer.id = "toast-container";
        document.body.appendChild(toastContainer);
    }

    let toast = document.createElement("div");
    toast.className = "toast " + type;
    toast.textContent = message;
    toastContainer.appendChild(toast);

    // Fade out and remove toast after 3 seconds
    setTimeout(() => {
        toast.style.opacity = "0";
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Wrapper function for toastr
const toastr = {
    success: (msg, title = "") => showToast(title ? `${title}: ${msg}` : msg, "success"),
    error: (msg, title = "") => showToast(title ? `${title}: ${msg}` : msg, "error"),
    warning: (msg, title = "") => showToast(title ? `${title}: ${msg}` : msg, "warning")
};


document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM fully loaded and parsed");
    
    fetchLabs();  // Fetch lab data
    fetchLabIds(); // Fetch lab IDs
});


function fetchLabIds() {
    console.log("Fetching Lab IDs...");

    fetch("http://localhost/lab_test_comparision/admin/core/get_lab_ids.php")
        .then(response => {
            if (!response.ok) {
                throw new Error("HTTP error! Status: " + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log("Fetched Lab IDs:", data);

            if (data.status === "success") {
                let labIdSelect = document.getElementById("labId");

                if (!labIdSelect) {
                    console.error("Element with ID 'labId' not found.");
                    return;
                }

                labIdSelect.innerHTML = '<option value="">Select Lab Name</option>'; // Reset options

                data.data.forEach(labId => {
                    let option = document.createElement("option");
                    option.value = labId;
                    option.textContent = labId;
                    labIdSelect.appendChild(option);
                });
            } else {
                console.error("Failed to fetch Lab IDs:", data.message);
            }
        })
        .catch(error => {
            console.error("Error fetching Lab IDs:", error);
        });
}

function displayLabs(data) {
    console.log("Displaying labs...", data);

    let tableBody = document.getElementById("lab-table-body");
    let labDropdown = document.getElementById("filter-labid"); // Get dropdown element

    if (!tableBody) {
        console.error("Element with ID 'lab-table-body' not found.");
        return;
    }
    if (!labDropdown) {
        console.error("Element with ID 'filter-labid' not found.");
        return;
    }

    // Clear previous data
    tableBody.innerHTML = "";
    labDropdown.innerHTML = '<option value="">Select Lab</option>'; // Reset dropdown

    data.forEach(lab => {
        // Populate table
        let row = document.createElement("tr");
        row.innerHTML = `
            <td>${lab.labid}</td>
            <td>${lab.LabName}</td>
            <td>${lab.CreationTime}</td>
            <td>
                <button onclick="editLab(${lab.labid})">Edit</button>
                <button onclick="deleteLab(${lab.labid})">Delete</button>
            </td>
        `;
        tableBody.appendChild(row);

        // Populate dropdown
        let option = document.createElement("option");
        option.value = lab.labid;
        option.textContent = lab.labname;
        labDropdown.appendChild(option);
    });
}







function editLab(labId) {
    alert("Edit function for Lab ID: " + labId);
    // Implement edit logic
}

function deleteLab(labId) {
    if (confirm("Are you sure you want to delete this lab?")) {
        alert("Delete function for Lab ID: " + labId);
        // Implement delete logic
    }
}

// Fetch data on page load
document.addEventListener("DOMContentLoaded", fetchLabs);





let testData = []; // Store test data globally

    // Fetch labs and populate dropdown
    function fetchLabs() {
        fetch("http://localhost/lab_test_comparision/admin/core/get_labs.php")
            .then(response => response.json())
            .then(data => {
                console.log("Fetched Lab Data:", data);
    
                if (!Array.isArray(data) || data.length === 0) {
                    console.warn("No labs found.");
                    displayLabs([]); // Show empty message
                    return;
                }
    
                displayLabs(data); // Call function to display data
            })
            .catch(error => {
                console.error("Error fetching lab data:", error);
                alert("Error fetching data! Check console for details.");
            });
    }
    
    
    
    
    // Fetch test data
    function fetchTests() {
        fetch("http://localhost/lab_test_comparision/admin/core/get_tests.php")
            .then(response => response.json())
            .then(data => {
                testData = data; // Store data globally
                displayTests(data);
            })
            .catch(error => {
                console.error("Error fetching test data:", error);
            });
    }

    // Display test data in table
    function displayTests(tests) {
        const tableBody = document.getElementById("test-table-body");
        tableBody.innerHTML = ""; // Clear previous data

        tests.forEach(test => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${test.labname}</td>
                <td>${test.testname}</td>
                <td>${test.fullamount}</td>
                <td>${test.currentamount}</td>
                <td>${test.discount}%</td>
                <td>${test.discountinamount}</td>
                <td>
                <button onclick='openEditModal(${JSON.stringify(test)})'>Edit</button>
                <button onclick="deleteTest(${test.testid})">Delete</button>

            </td>
            `;
            tableBody.appendChild(row);
        });
    }

    // Filter test data based on selected Lab ID
    function filterTests() {
        const selectedLabId = document.getElementById("filter-labid").value;

        const filteredTests = testData.filter(test => {
            return selectedLabId === "" || test.labid == selectedLabId;
        });

        displayTests(filteredTests);
    }

  
// Fetch tests on page load
document.addEventListener("DOMContentLoaded", fetchTests);



function fetchLabsnames() {
    fetch("http://localhost/lab_test_comparision/admin/core/get_labs.php")
    .then(response => response.json())
    .then(data => {
        console.log("Fetched Labs:", data); // Debugging

        const labDropdown = document.getElementById("filter-labid");
        if (!labDropdown) {
            console.error("Dropdown element not found!");
            return;
        }

        // Forcefully clear and repopulate
        labDropdown.innerHTML = ""; // Clear all previous options
        
        // Add the default "Select Lab" option
        let defaultOption = document.createElement("option");
        defaultOption.value = "";
        defaultOption.textContent = "Select Lab";
        defaultOption.selected = true;
        labDropdown.appendChild(defaultOption);

        // Populate with lab names
        data.forEach(lab => {
            let option = document.createElement("option");
            option.value = lab.labid; // Set Lab ID as value
            option.textContent = lab.LabName; // Set Lab Name as display
            labDropdown.appendChild(option);
        });

        console.log("Dropdown Updated:", labDropdown.innerHTML); // Debugging
    })
    .catch(error => {
        console.error("Error fetching labs:", error);
    });
}

// Call function when the page loads
document.addEventListener("DOMContentLoaded", function() {
    fetchLabsnames();
});



let selectedTestId = null; // Store the test ID

function openEditModal(test) {
    selectedTestId = test.testid;

    document.getElementById("editLabName").value = test.labname;
    document.getElementById("editTestName").value = test.testname;
    document.getElementById("editFullAmount").value = test.fullamount;
    document.getElementById("editDiscount").value = test.discount;
    document.getElementById("editCurrentAmount").value = test.currentamount;
    document.getElementById("editDiscountAmount").value = test.discountinamount;

    document.getElementById("editModal").style.display = "block";
}

function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
}

function calculateUpdatedAmount() {
    let fullAmount = parseFloat(document.getElementById("editFullAmount").value);
    let discountPercent = parseFloat(document.getElementById("editDiscount").value);

    if (isNaN(discountPercent) || discountPercent < 0 || discountPercent > 100) {
        alert("Enter a valid discount percentage between 0 and 100.");
        return;
    }

    // Calculate discount in amount
    let discountAmount = (fullAmount * discountPercent) / 100;
    let currentAmount = fullAmount - discountAmount;

    // Update fields dynamically
    document.getElementById("editDiscountAmount").value = discountAmount.toFixed(2);
    document.getElementById("editCurrentAmount").value = currentAmount.toFixed(2);
}


function saveTestChanges() {
    const discountPercent = document.getElementById("editDiscount").value;
    const discountAmount = document.getElementById("editDiscountAmount").value;
    const currentAmount = document.getElementById("editCurrentAmount").value;

    if (!discountPercent || isNaN(discountPercent)) {
        alert("Please enter a valid discount percentage.");
        return;
    }

    fetch("http://localhost/lab_test_comparision/admin/core/update_test.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            testid: selectedTestId,
            discount: discountPercent,
            discountinamount: discountAmount,
            currentamount: currentAmount
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert("Test discount updated successfully!");
            closeEditModal();
            location.reload(); // Refresh table to show updated data
        } else {
            alert("Error updating test: " + data.message);
        }
    })
    .catch(error => console.error("Error updating test:", error));
}


function deleteTest(testid) {
    if (confirm("Are you sure you want to delete this test?")) {
        fetch("http://localhost/lab_test_comparision/admin/core/delete_test.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `testid=${testid}`,
        })
        .then(response => response.json())
        .then(data => {
            console.log("API Response:", data); // Debugging
            if (data.status === "success") {
                alert("Test deleted successfully!");
                location.reload(); // Refresh table
            } else {
                alert("Error deleting test: " + data.message);
            }
        })
        .catch(error => console.error("Error:", error));
    }
}









function viewDetails(bookingID) {
    fetch('http://localhost/lab_test_comparision/admin/core/fetch_booking_details.php?booking_id=' + bookingID)
    .then(response => response.json())
    .then(data => {
        console.log("API Response:", data); // Debugging
        document.getElementById("modalBookingID").textContent = bookingID;

        let detailsHTML = "";
        data.forEach(test => {
            detailsHTML += `
                <tr>
                    <td>${test.TestName}</td>
                    <td>${test.LabName}</td>
                    <td>₹${test.OriginalPrice}</td>
                    <td>${test.Discount}%</td>
                    <td>₹${test.FinalPrice}</td>
                </tr>`;
        });

        document.getElementById("modalTestDetails").innerHTML = detailsHTML;
        document.getElementById("detailsModal").style.display = "block";
    })
    .catch(error => console.error("Error:", error));
}
