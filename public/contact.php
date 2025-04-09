<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | LabTest</title>
    <link rel="stylesheet" href="css/contacts.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

    <!-- Navbar -->
    <header>
        <nav class="navbar">
            <div class="logo">LabTest</div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="test.php">Available Tests</a></li>
              
                <li><a href="contact.php" class="active">Contact</a></li>
                <li><a href="login.php">Cart Tests (<span id="cartCount">0</span>)</a></li>
            </ul>
            <div class="menu-toggle" onclick="toggleMenu()">
                <i class="fa fa-bars"></i>
            </div>
        </nav>
    </header>
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

    <!-- Contact Section -->
<section class="contact-section">
    <h2>Contact Us</h2>
    <div class="contact-container">
        <!-- Contact Info -->
        <div class="contact-info">
            <div class="info-box">
                <i class="fas fa-map-marker-alt"></i>
                <p><strong>Address:</strong> 123 Lab Street, City, Country</p>
            </div>
            <div class="info-box">
                <i class="fas fa-phone"></i>
                <p><strong>Phone:</strong> +1 234 567 890</p>
            </div>
            <div class="info-box">
                <i class="fas fa-envelope"></i>
                <p><strong>Email:</strong> info@labtest.com</p>
            </div>
            <div class="info-box">
                <i class="fas fa-clock"></i>
                <p><strong>Hours:</strong> Mon-Fri: 9 AM - 6 PM</p>
            </div>

            <!-- Social Media Links -->
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="contact-form">
            <h3>Send Us a Message</h3>
            <form>
                <input type="text" placeholder="Your Name" required>
                <input type="email" placeholder="Your Email" required>
                <input type="tel" placeholder="Your Phone" required>
                <textarea placeholder="Your Message" rows="5" required></textarea>
                <button type="submit">Submit</button>
            </form>
        </div>

        <!-- Google Map -->
        <div class="contact-map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d241317.11609947314!2d72.7411010276982!3d19.08219783958454!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7b5d78a60a4f3%3A0x37a8f0bfcaf3e0b4!2sMumbai!5e0!3m2!1sen!2sin!4v1635575268773!5m2!1sen!2sin" width="100%" height="300" style="border:0;" allowfullscreen loading="lazy"></iframe>
        </div>
    </div>
</section>

     <!-- Footer Section -->

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
        function toggleMenu() {
            const menu = document.querySelector(".nav-links");
            menu.classList.toggle("active");
        }
    </script>

</body>
</html>
