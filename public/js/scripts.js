document.addEventListener("DOMContentLoaded", function () {
    const toggleButton = document.getElementById("menu-toggle");
    const navLinks = document.getElementById("nav-links");

    // Toggle the menu
    toggleButton.addEventListener("click", function () {
        navLinks.classList.toggle("show");
    });

    // Close menu when clicking a link
    navLinks.addEventListener("click", function (event) {
        if (event.target.tagName === "A") {
            navLinks.classList.remove("show");
        }
    });

    // Form validation
    document.getElementById("contact-form").addEventListener("submit", function (event) {
        event.preventDefault();

        let name = document.getElementById("name").value.trim();
        let email = document.getElementById("email").value.trim();
        let message = document.getElementById("message").value.trim();
        let error = "";

        if (name === "") {
            error += "Name is required.\n";
        }

        if (email === "" || !/^\S+@\S+\.\S+$/.test(email)) {
            error += "Valid email is required.\n";
        }

        if (message === "") {
            error += "Message cannot be empty.\n";
        }

        if (error) {
            alert(error);
        } else {
            alert("Form submitted successfully!");
            this.reset(); // Reset form after successful submission
        }
    });
});



// Fetch test data
function fetchTests() {
    fetch("http://localhost/lab_test_comparision/public/api/get_tests.php")
        .then(response => response.json())
        .then(data => {
            testData = data; // Store data globally
            displayTests(data);
        })
        .catch(error => {
            console.error("Error fetching test data:", error);
        });
}



// Initialize cart from localStorage if exists
let cart = JSON.parse(localStorage.getItem("cart")) || [];

// Display test data in table
// Display test data in table
function displayTests(tests) {
    const testContainer = document.getElementById("testList");
    testContainer.innerHTML = "";

    // Apply styles to the test container
    testContainer.style.display = "grid";
    testContainer.style.gap = "20px";
    testContainer.style.padding = "20px";

    // Function to set columns based on window width
    function setGridColumns() {
        if (window.innerWidth <= 600) {
            testContainer.style.gridTemplateColumns = "1fr";
        } else if (window.innerWidth <= 1024) {
            testContainer.style.gridTemplateColumns = "repeat(2, 1fr)";
        } else {
            testContainer.style.gridTemplateColumns = "repeat(3, 1fr)";
        }
    }

    // Initial call and add resize listener
    setGridColumns();
    window.addEventListener("resize", setGridColumns);

    // Fill extra slots to maintain 3-column layout on large screen
    const columns = window.innerWidth <= 600 ? 1 : window.innerWidth <= 1024 ? 2 : 3;
    const extraSlots = tests.length % columns === 0 ? 0 : columns - (tests.length % columns);
    for (let i = 0; i < extraSlots; i++) {
        tests.push({ testname: "", LabName: "", fullamount: "", currentamount: "", discount: "", description: "", testid: `empty-${i}` });
    }

    tests.forEach(test => {
        const testCard = document.createElement("div");
        testCard.classList.add("test-card");

        testCard.style.border = "1px solid #ddd";
        testCard.style.borderRadius = "10px";
        testCard.style.padding = "15px";
        testCard.style.boxShadow = "0 4px 8px rgba(0, 0, 0, 0.1)";
        testCard.style.backgroundColor = "#fff";
        testCard.style.transition = "transform 0.3s ease-in-out";
        testCard.style.cursor = "pointer";
        testCard.style.textAlign = "center";
        testCard.style.minHeight = "150px";

        // Hide filler
        if (test.testname === "") {
            testCard.style.visibility = "hidden";
        }

        // Check if this test is already in cart
        const isInCart = cart.find(item => item.testid === test.testid);

        testCard.innerHTML = test.testname !== "" ? `
            <div class="test-item">
                <h3 style="color: #007bff; margin-bottom: 10px;">${test.testname}</h3>
                <p style="font-size: 14px; color: #555;">Lab: ${test.LabName}</p>
                <div class="price-section" style="margin: 10px 0;">
                    <span class="original-price" style="text-decoration: line-through; color: red; font-weight: bold; margin-right: 5px;">
                        ₹${test.fullamount}
                    </span>
                    <span class="discount-price" style="color: green; font-weight: bold; font-size: 18px;">
                        ₹${test.currentamount}
                    </span>
                    <span class="discount-percentage" style="color: #ff5733; font-size: 14px; margin-left: 5px;">
                        (${test.discount}% off)
                    </span>
                </div>
                <p style="font-size: 14px; color: #666;">${test.description}</p>
                <button class="add-to-cart" id="cart-btn-${test.testid}"
                    onclick="toggleCart('${test.testid}', '${test.testname}', '${test.LabName}', '${test.currentamount}', '${test.discount}', '${test.fullamount}')"
                    style="padding: 10px 15px; background: linear-gradient(90deg, #005F73, #0A9396); color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
                    ${isInCart ? "Remove from Cart" : "Add To Cart"}
                </button>
            </div>
        ` : "";

        testCard.addEventListener("mouseenter", () => {
            testCard.style.transform = "scale(1.05)";
        });

        testCard.addEventListener("mouseleave", () => {
            testCard.style.transform = "scale(1)";
        });

        testContainer.appendChild(testCard);
    });

    updateCartCount();
}


// Update Cart Count in Navbar
function updateCartCount() {
    document.getElementById("cartCount").textContent = cart.length;
}

// Toggle Add/Remove from Cart
function toggleCart(testid, testname, LabName, currentamount, discount, fullamount) {
    const index = cart.findIndex(item => item.testid === testid);
    const button = document.getElementById(`cart-btn-${testid}`);

    if (index === -1) {
        cart.push({ testid, testname, LabName, currentamount, discount, fullamount });
        if (button) button.textContent = "Remove from Cart";
    } else {
        cart.splice(index, 1);
        if (button) button.textContent = "Add To Cart";
    }

    localStorage.setItem("cart", JSON.stringify(cart)); // Save cart state
    updateCartCount();
}

// On page load, restore cart count
document.addEventListener("DOMContentLoaded", () => {
    updateCartCount();
});



// Filter tests based on search input and selected lab
function filterTests() {
    const selectedLabName = document.getElementById("filterLab").value.toLowerCase();
    const searchQuery = document.getElementById("searchTest").value.toLowerCase();

    const filteredTests = testData.filter(test => {
        const matchLab = selectedLabName === "" || test.LabName.toLowerCase() === selectedLabName;
        const matchTest = searchQuery === "" || test.testname.toLowerCase().includes(searchQuery);
        
        return matchLab && matchTest; // Both must match
    });

    displayTests(filteredTests);
}

// Add event listeners to trigger filtering
document.getElementById("searchTest").addEventListener("keyup", filterTests);
document.getElementById("filterLab").addEventListener("change", filterTests);



// Fetch tests on page load
document.addEventListener("DOMContentLoaded", fetchTests);







document.addEventListener("DOMContentLoaded", function () {
    fetch("http://localhost/lab_test_comparision/public/api/get_labnames.php") // Call the PHP API
        .then(response => response.json())
        .then(data => {
            const labDropdown = document.getElementById("filterLab");
            
            data.forEach(lab => {
                const option = document.createElement("option");
                option.value = lab.LabName;
                option.textContent = lab.LabName;
                labDropdown.appendChild(option);
            });
        })
        .catch(error => {
            console.error("Error fetching lab names:", error);
        });
});