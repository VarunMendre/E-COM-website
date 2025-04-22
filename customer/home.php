<?php
// Start the session to access user login status
session_start();

// Check if the user is logged in; if not, show an unauthorized message and stop further execution
if (!isset($_SESSION['login_status']) || $_SESSION['login_status'] == false) {
    echo "<div style='text-align: center; margin-top: 50px; color: red; font-size: 1.2em;'>UnAuthorized Login Access</div>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home page</title>
</head>
<body>
    <?php
        // Include the menu, slider, homepage promotional section, and DB connection
        include "menu.html";
        include "slider.html";
        include "home-produ.html";
        include_once "../shared/connection.php";
    ?>

    <div class="container">
        <div class="header">
            <h1>Welcome to Our Store</h1>
            <p>Discover amazing products at great prices</p>
        </div>
        
        <?php
            // Fetch all active products (status = 1) from the database
            $sql_result = mysqli_query($conn, "SELECT * FROM product WHERE status = 1");

            // Loop through each product and display it in a card layout
            while($dbrow = mysqli_fetch_assoc($sql_result)) {   
                echo "<div class='pdt-card'>
                    <div class='name'>$dbrow[name]</div>
                    <div class='price'>₹$dbrow[price]</div>
                    <img class='pdt-img' src='$dbrow[impath]' alt='$dbrow[name]'>
                    <div class='detail'>$dbrow[detail]</div>
                    <div>
                        <a href='addcart.php?pid=$dbrow[pid]'>
                            <button class='add-to-cart'>Add to Cart</button>
                        </a>
                    </div>
                </div>";
            }
        ?>
    </div>

    <script>
        // JavaScript code to manage slider functionality

        const slider = document.querySelector('.slider');          // The container for all slides
        const slides = document.querySelectorAll('.slide');        // All individual slides
        const dots = document.querySelectorAll('.dot');            // Navigation dots
        const prevBtn = document.querySelector('.prev-btn');       // Previous button
        const nextBtn = document.querySelector('.next-btn');       // Next button
        
        let currentSlide = 0;                                      // Track current slide index
        const slideCount = slides.length;                          // Total number of slides

        // Function to update the slide view based on currentSlide index
        function updateSlider() {
            slider.style.transform = `translateX(-${currentSlide * 100}%)`;
            
            // Highlight the active dot
            dots.forEach(dot => dot.classList.remove('active'));
            dots[currentSlide].classList.add('active');
        }

        // Move to the next slide
        function nextSlide() {
            currentSlide = (currentSlide + 1) % slideCount;
            updateSlider();
        }

        // Move to the previous slide
        function prevSlide() {
            currentSlide = (currentSlide - 1 + slideCount) % slideCount;
            updateSlider();
        }

        // Add event listeners for navigation buttons
        nextBtn.addEventListener('click', nextSlide);
        prevBtn.addEventListener('click', prevSlide);

        // Add click event for each dot to jump to that slide
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentSlide = index;
                updateSlider();
            });
        });

        // Auto-slide every 5 seconds
        setInterval(nextSlide, 5000);
    </script>

    <?php include "footer.html"; // Include the footer ?>
</body>
</html>
