

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Tests - LabTest</title>
    <link rel="stylesheet" href="css/tests.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    
    <header>
       
    <nav class="navbar">
        <div class="logo">LabTest</div>
        <ul class="nav-links">
            <li><a href="index.php" class="active">Home</a></li>
            <li><a href="test.php">Available Tests</a></li>
           
            <li><a href="contact.php">Contact</a></li>
            <li><a href="login.php">Cart Tests (<span id="cartCount">0</span>)</a></li>

        </ul>
        <div class="menu-toggle" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </div>
    </nav>

    </header>

    <section class="filter-search">
    <input type="text" id="searchTest" placeholder="Search for tests..." onkeyup="searchTests()">
    <select id="filterLab" onchange="filterTests()">
        <option value="">Select Lab</option>
    </select>
</section>

    <section class="tests">
   
    <h2>Available Lab Tests</h2>
    <div class="test-container" id="testList">
        <p>Loading tests...</p>
    </div>
</section>




  <!-- Footer Section -->
  <footer>
        <div class="footer-content">
            <p>&copy; 2025 LabTest. All rights reserved.</p>
            <div class="social-icons">
                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
    </footer>

    <script>
        function searchTests() {
            let input = document.getElementById("searchTest").value.toLowerCase();
            let tests = document.querySelectorAll(".test-card");
            tests.forEach(test => {
                let testName = test.querySelector("h3").innerText.toLowerCase();
                test.style.display = testName.includes(input) ? "block" : "none";
            });
        }

        function filterTests() {
            let selectedLab = document.getElementById("filterLab").value;
            let selectedPrice = document.getElementById("filterPrice").value.split("-");
            let selectedDiscount = document.getElementById("filterDiscount").value;
            let selectedLocation = document.getElementById("filterLocation").value;
            
            let tests = document.querySelectorAll(".test-card");
            tests.forEach(test => {
                let lab = test.getAttribute("data-lab");
                let price = parseInt(test.getAttribute("data-price"));
                let discount = parseInt(test.getAttribute("data-discount"));
                let location = test.getAttribute("data-location");
                
                let priceMatch = !selectedPrice[0] || (price >= selectedPrice[0] && price <= selectedPrice[1]);
                let discountMatch = !selectedDiscount || discount >= selectedDiscount;
                let labMatch = !selectedLab || lab === selectedLab;
                let locationMatch = !selectedLocation || location === selectedLocation;
                
                test.style.display = (priceMatch && discountMatch && labMatch && locationMatch) ? "block" : "none";
            });
        }
        // ✅ FAQ Toggle Function
        function toggleMenu() {
    let menu = document.querySelector(".nav-links");
    menu.classList.toggle("active");
}

    </script>

<script src="js/scripts.js"></script>
</body>
</html>