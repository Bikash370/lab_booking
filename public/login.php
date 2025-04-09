<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Book Your Tests</title>
    <link rel="stylesheet" href="css/logins.css">  <!-- Link to external CSS file -->
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">  <!-- FontAwesome Icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script> <!-- jsPDF for PDF generation -->
    
</head>
<body>

    <div class="container">
        <h2>Your Cart - Book Your Tests</h2>
        
        <table>
            <thead>
                <tr>
                    <th>Test Name</th>
                    <th>Lab Name</th>
                    <th>Original Price</th>
                    <th>Discount</th>
                    <th>Final Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="cartItems">
                <!-- Cart items will be loaded here -->
            </tbody>
        </table>

        <div class="total-price">
            <h3>Total Amount: ₹<span id="totalAmount">0</span></h3>
        </div>

        <div class="patient-info">
    <h3>Patient Information</h3>
    <form id="appointmentForm">
        <label>Full Name</label>
        <input type="text" id="patientName" placeholder="Enter Patient Name" required>

        <label>Age</label>
        <input type="number" id="patientAge" placeholder="Enter Age" required>

        <label>Sex</label>
        <select id="patientSex" required >
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>

        <label>Mobile Number</label>
        <input type="text" id="patientMobile" placeholder="Enter Mobile Number" required 
               pattern="[0-9]{10}" maxlength="10" title="Enter a valid 10-digit mobile number">

        <button type="submit" class="book-btn">Book Appointment</button>
    </form>
</div>

        <a href="test.php" class="back-link">← Back to Tests</a>
    </div>
    <a href="https://wa.me/919876543210" target="_blank"
   style="position: fixed;
          bottom: 20px;
          right: 20px;
          background-color: #25D366;
          color: white;
          width: 60px;
          height: 60px;
          display: flex;
          align-items: center;
          justify-content: center;
          border-radius: 50%;
          font-size: 28px;
          cursor: pointer;
          text-align: center;
          z-index: 9999;
          transition: transform 0.3s ease, box-shadow 0.3s ease;"
   onmouseover="this.style.transform='scale(1.2)'; this.style.boxShadow='0 0 15px #25D366';"
   onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';">
    <i class="fab fa-whatsapp"></i>
</a>
    <script>
        let cart = JSON.parse(localStorage.getItem("cart")) || [];

        function displayCart() {
            const cartTable = document.getElementById("cartItems");
            const totalAmountEl = document.getElementById("totalAmount");
            cartTable.innerHTML = "";

            if (cart.length === 0) {
                cartTable.innerHTML = "<tr><td colspan='6'>No tests in cart.</td></tr>";
                totalAmountEl.innerText = "0";
                return;
            }

            let totalAmount = 0;

            cart.forEach((test, index) => {
                const row = document.createElement("tr");

                row.innerHTML = `
                    <td>${test.testname}</td>
                    <td>${test.LabName}</td>
                    <td><s>₹${test.fullamount}</s></td>
                    <td>${test.discount}%</td>
                    <td><b>₹${test.currentamount}</b></td>
                    <td><button class="remove-btn" onclick="removeFromCart(${index})">Remove</button></td>
                `;

                totalAmount += parseFloat(test.currentamount);
                cartTable.appendChild(row);
            });

            totalAmountEl.innerText = totalAmount.toFixed(2);
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            localStorage.setItem("cart", JSON.stringify(cart));
            displayCart();
        }

        document.getElementById("appointmentForm").addEventListener("submit", function (event) {
    event.preventDefault();

    const patientName = document.getElementById("patientName").value.trim();
    const patientAge = document.getElementById("patientAge").value.trim();
    const patientSex = document.getElementById("patientSex").value.trim();
    const patientMobile = document.getElementById("patientMobile").value.trim();
    const totalAmount = document.getElementById("totalAmount").innerText;

    if (!patientName || !patientAge || !patientSex || !patientMobile) {
        alert("Please fill all patient details.");
        return;
    }

    // Validate Mobile Number (must be exactly 10 digits)
    if (!/^\d{10}$/.test(patientMobile)) {
        alert("Please enter a valid 10-digit mobile number.");
        return;
    }

    if (cart.length === 0) {
        alert("No tests selected. Please add tests before booking.");
        return;
    }

    // Map cart data to include all required fields
    const formattedTests = cart.map(test => ({
        testname: test.testname,
        LabName: test.LabName,
        fullamount: test.fullamount,
        discount: test.discount,
        currentamount: test.currentamount
    }));

    // Prepare the data to send
    const bookingData = {
        patientName: patientName,
        patientAge: patientAge,
        patientSex: patientSex,
        patientMobile: patientMobile,
        totalAmount: totalAmount,
        tests: formattedTests
    };

    // Send data to PHP using Fetch API
    fetch("http://localhost/lab_test_comparision/public/api/book_appointment.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(bookingData),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            generatePDF(patientName, patientAge, patientSex, formattedTests, totalAmount);
            alert("Appointment booked successfully!");
            localStorage.removeItem("cart");

            // Store a flag in localStorage to indicate test.php should refresh
            localStorage.setItem("refreshTestPage", "true");

            // Redirect to index.php after a short delay
            setTimeout(() => {
                window.location.href = "index.php";
            }, 100);
        } else {
            alert("Failed to book appointment. Please try again.");
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Something went wrong.");
    });
});


function generatePDF(patientName, patientAge, patientSex, cart, totalAmount) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.setFontSize(18);
    doc.text("Appointment Confirmation", 20, 20);
    doc.setFontSize(12);
    doc.text(`Patient Name: ${patientName}`, 20, 40);
    doc.text(`Age: ${patientAge}`, 20, 50);
    doc.text(`Sex: ${patientSex}`, 20, 60);
    doc.text(`Tests Booked:`, 20, 80);

    let yPos = 90;
    cart.forEach((test, index) => {
        doc.text(`${index + 1}. ${test.testname} - ${test.LabName} (₹${test.currentamount})`, 20, yPos);
        yPos += 10;
    });

    doc.text(`Total Amount: ₹${totalAmount}`, 20, yPos + 10);
    doc.save("Appointment_Details.pdf");
}

        displayCart();
    </script>

</body>
</html>
