<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="css/customerdashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">  
</head>
<body>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <div class="logo">LabTest</div>
        <ul class="menu">
            <li class="active" onclick="openTab(event, 'book-test')"><i class="fa fa-flask"></i> Book Test</li>
            <li onclick="openTab(event, 'my-bookings')"><i class="fa fa-list"></i> My Bookings</li>
            <li onclick="openTab(event, 'account-details')"><i class="fa fa-user"></i> Account Details</li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <div class="menu-toggle" onclick="toggleMenu()">
                <i class="fa fa-bars"></i>
            </div>
            <h2>Customer Dashboard</h2>
        </header>

        <!-- Book Test Tab -->
        <!-- Book Test Tab -->
<section id="book-test" class="tab-content active">
    <h3>Book a Lab Test</h3>

    <!-- Filter and Search Section -->
    <section class="filter-search">
        <input type="text" id="searchTest" placeholder="Search for tests..." onkeyup="searchTests()">
        <select id="filterLab" onchange="filterTests()">
            <option value="">Select Lab</option>
            <option value="Lab A">Lab A</option>
            <option value="Lab B">Lab B</option>
            <option value="Lab C">Lab C</option>
        </select>
        <select id="filterPrice" onchange="filterTests()">
            <option value="">Select Price Range</option>
            <option value="0-500">Under 500</option>
            <option value="500-1000">500 - 1000</option>
            <option value="1000-2000">1000 - 2000</option>
        </select>
        <select id="filterDiscount" onchange="filterTests()">
            <option value="">Select Discount</option>
            <option value="10">10% or more</option>
            <option value="20">20% or more</option>
            <option value="30">30% or more</option>
        </select>
        <select id="filterLocation" onchange="filterTests()">
            <option value="">Select Location</option>
            <option value="Delhi">Delhi</option>
            <option value="Mumbai">Mumbai</option>
            <option value="Bangalore">Bangalore</option>
        </select>
    </section>
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
    <!-- Available Tests Section -->
    <section class="tests">
        <h2>Available Lab Tests</h2>
        <div class="test-container" id="testList">
            <div class="test-card" data-lab="Lab A" data-price="800" data-discount="20" data-location="Delhi">
                <h3>Blood Test</h3>
                <p>Lab: Lab A</p>
                <p>Price: <s>1000</s> <b>800</b> (20% off)</p>
                <p>Location: Delhi</p>
                <button class="book-btn">Book Now</button>
            </div>
            <div class="test-card" data-lab="Lab B" data-price="1200" data-discount="10" data-location="Mumbai">
                <h3>Thyroid Test</h3>
                <p>Lab: Lab B</p>
                <p>Price: <s>1350</s> <b>1200</b> (10% off)</p>
                <p>Location: Mumbai</p>
                <button class="book-btn">Book Now</button>
            </div>
            <div class="test-card" data-lab="Lab C" data-price="600" data-discount="30" data-location="Bangalore">
                <h3>Diabetes Test</h3>
                <p>Lab: Lab C</p>
                <p>Price: <s>900</s> <b>600</b> (30% off)</p>
                <p>Location: Bangalore</p>
                <button class="book-btn">Book Now</button>
            </div>
        </div>
    </section>
</section>


        <!-- My Bookings Tab -->
        <section id="my-bookings" class="tab-content">
            <h3>My Bookings</h3>
            <table class="bookings-table">
                <thead>
                    <tr>
                        <th>Test Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Blood Test</td>
                        <td>12-03-2025</td>
                        <td>10:00 AM</td>
                        <td>Confirmed</td>
                    </tr>
                    <tr>
                        <td>Thyroid Test</td>
                        <td>15-03-2025</td>
                        <td>2:00 PM</td>
                        <td>Pending</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Account Details Tab -->
        <section id="account-details" class="tab-content">
            <h3>Account Details</h3>
            <form class="account-form">
                <label>Name</label>
                <input type="text" value="John Doe" disabled>

                <label>Email</label>
                <input type="email" value="johndoe@example.com" disabled>

                <label>Phone</label>
                <input type="tel" value="9876543210" disabled>

                <button class="btn">Update Profile</button>
            </form>
        </section>
    </div>

    <script>


function searchTests() {
    let input = document.getElementById('searchTest').value.toLowerCase();
    let testCards = document.querySelectorAll('.test-card');

    testCards.forEach(card => {
        let testName = card.querySelector('h3').textContent.toLowerCase();
        if (testName.includes(input)) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    });
}

function filterTests() {
    let selectedLab = document.getElementById('filterLab').value;
    let selectedPrice = document.getElementById('filterPrice').value;
    let selectedDiscount = document.getElementById('filterDiscount').value;
    let selectedLocation = document.getElementById('filterLocation').value;

    let testCards = document.querySelectorAll('.test-card');

    testCards.forEach(card => {
        let cardLab = card.getAttribute('data-lab');
        let cardPrice = parseInt(card.getAttribute('data-price'));
        let cardDiscount = parseInt(card.getAttribute('data-discount'));
        let cardLocation = card.getAttribute('data-location');

        let priceMin = 0, priceMax = Infinity;

        if (selectedPrice) {
            let priceRange = selectedPrice.split('-');
            priceMin = parseInt(priceRange[0]);
            priceMax = parseInt(priceRange[1]) || Infinity;
        }

        let matchesFilter = (!selectedLab || cardLab === selectedLab) &&
                            (!selectedLocation || cardLocation === selectedLocation) &&
                            (!selectedPrice || (cardPrice >= priceMin && cardPrice <= priceMax)) &&
                            (!selectedDiscount || cardDiscount >= parseInt(selectedDiscount));

        card.style.display = matchesFilter ? "block" : "none";
    });
}




       function openTab(event, tabId) {

        
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');

    document.querySelectorAll('.menu li').forEach(li => li.classList.remove('active'));
    event.currentTarget.classList.add('active');

    // Hide sidebar on smaller screens when a menu item is clicked
    const sidebar = document.querySelector(".sidebar");
    if (window.innerWidth <= 768) {
        sidebar.classList.remove("active");
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.querySelector(".sidebar");
    const toggleBtn = document.createElement("div");

    toggleBtn.classList.add("menu-toggle");
    toggleBtn.innerHTML = "&#9776;"; // Menu icon
    document.body.appendChild(toggleBtn);

    toggleBtn.addEventListener("click", function () {
        sidebar.classList.toggle("active");
    });

    // Close sidebar when a menu item is clicked (for responsive mode)
    document.querySelectorAll('.menu li').forEach(item => {
        item.addEventListener("click", function () {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove("active");
            }
        });
    });
});




    </script>
    
</body>
</html>
