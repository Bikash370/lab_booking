<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Test Booking System</title>
    <link rel="stylesheet" href="css/indexs.css">  <!-- Link to external CSS file -->
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


</head>
<body>

    <!-- Navbar Section -->
    <header>
    <nav class="navbar">
        <div class="logo">LabTest</div>
        <ul class="nav-links">
            <li><a href="index.php" class="active">Home</a></li>

            <!-- WhatsApp Icon -->
           

            <li><a href="test.php">Available Tests</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="login.php">Cart Tests (<span id="cartCount">0</span>)</a></li>
        </ul>

        <div class="menu-toggle" onclick="toggleMenu()">
            <i class="fa fa-bars"></i>
        </div>
    </nav>
</header>


    <!-- Hero Section -->
    <section class="hero">
        <h1>Book Lab Tests Online</h1>
        <p>Get accurate test results from trusted labs, hassle-free.</p>
        <a href="test.php" class="cta-btn">Book a Test</a>
    </section>

    <section class="lab-partners">
    <div class="marquee">
        <div class="marquee-content">
            <div class="lab"><img src="https://upload.wikimedia.org/wikipedia/commons/a/a7/Metropolis_Healthcare_Logo_Green_Background.png" alt="Lab 1">Metropolics</div>
            <div class="lab"><img src="https://vectorseek.com/wp-content/uploads/2023/09/Dr-Lal-Path-Labs-Logo-Vector.svg-.png" alt="Lab 2">Lalpathlab</div>
            <div class="lab"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/50/Thyrocare_new_Logo2022.svg/1200px-Thyrocare_new_Logo2022.svg.png" alt="Lab 3"> Thyrocare</div>
            <div class="lab"><img src="https://mttsindia.com/english/assets/img/APOLLO.jpg" alt="Lab 4"> APPOLO</div>
            <div class="lab"><img src="https://utkalhospital.com/images/footer/utkal.png" alt="Lab 5"> Utkal Hospital</div>
            <div class="lab"><img src="https://lifecarehosp.com/wp-content/uploads/2023/02/LCH-LOGO-NEW.jpg" alt="Lab 6"> Care</div>
            <!-- Duplicate items for smooth looping -->
            <div class="lab"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a6/KIMS_Main_Logo_Col-01.jpg/800px-KIMS_Main_Logo_Col-01.jpg" alt="Lab 1"> KIIMS</div>
            <div class="lab"><img src="https://doctorlistingingestionpr.blob.core.windows.net/doctorprofilepic/1670499660844_ProviderLogo_sum_soa_logo.png" alt="Lab 2"> SUM</div>
            <div class="lab"><img src="https://www.igkchospitals.com/images/LOGO_igkc.png" alt="Lab 3"> IGKC</div>
           
        </div>
    </div>
</section>

    <!-- Popular Tests Section -->
    <section class="popular-tests">
        <h2>Popular Tests</h2>
        <div class="test-list">
            <div class="test-card">
                <h3>Blood Test</h3>
                <p>Comprehensive blood analysis.</p>
            </div>
            <div class="test-card">
                <h3>Thyroid Test</h3>
                <p>Check your thyroid levels.</p>
            </div>
            <div class="test-card">
                <h3>Diabetes Test</h3>
                <p>Monitor blood sugar levels.</p>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq">
        <h2>Frequently Asked Questions</h2>
        <div class="faq-item">
            <div class="question" onclick="toggleFAQ(this)">What is the process for booking a test? <i class="fa fa-chevron-down"></i></div>
            <div class="answer">You can book a test online by selecting the test and confirming your slot.</div>
        </div>
        <div class="faq-item">
            <div class="question" onclick="toggleFAQ(this)">How do I receive my reports? <i class="fa fa-chevron-down"></i></div>
            <div class="answer">Reports are delivered via email or can be accessed from your account.</div>
        </div>
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
      document.addEventListener("DOMContentLoaded", function () {
    // ✅ Menu Toggle
    const menuToggle = document.querySelector(".menu-toggle");
    const navLinks = document.querySelector(".nav-links");
    const body = document.body;

    menuToggle.addEventListener("click", function () {
        navLinks.classList.toggle("active");

        if (navLinks.classList.contains("active")) {
            body.classList.add("menu-open");
        } else {
            body.classList.remove("menu-open");
        }
    });

    // ✅ FAQ Toggle Function
    function toggleFAQ(element) {
        const answer = element.nextElementSibling;
        const icon = element.querySelector("i");

        // Close all other FAQ answers
        document.querySelectorAll(".answer").forEach(ans => {
            if (ans !== answer) {
                ans.style.display = "none";
                ans.previousElementSibling.querySelector("i").classList.remove("fa-chevron-up");
                ans.previousElementSibling.querySelector("i").classList.add("fa-chevron-down");
            }
        });

        // Toggle the clicked FAQ item
        if (answer.style.display === "block") {
            answer.style.display = "none";
            icon.classList.remove("fa-chevron-up");
            icon.classList.add("fa-chevron-down");
        } else {
            answer.style.display = "block";
            icon.classList.remove("fa-chevron-down");
            icon.classList.add("fa-chevron-up");
        }
    }

    // Attach click event to all FAQ items
    document.querySelectorAll(".question").forEach(question => {
        question.addEventListener("click", function () {
            toggleFAQ(this);
        });
    });
});


    </script>

</body>
</html>
