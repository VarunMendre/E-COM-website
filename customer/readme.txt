<?php
    session_start();
    if(!isset($_SESSION['login_status']) || $_SESSION['login_status']==false){
        echo"<div style='text-align: center; margin-top: 50px; color: red; font-size: 1.2em;'>UnAuthorized Login Access</div>";
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home page</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f4f4f4 url('colorful-shopping-bags.jpg') center/cover no-repeat; 
            padding: 20px; 
        }
        .pdt-card { 
            background: #fff; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
            margin: 10px; 
            padding: 20px; 
            width: 300px; 
            text-align: center; 
            display: inline-block;
            transition: .3s;
            overflow: hidden;
        }
        .pdt-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .pdt-img { 
            width: 100%; 
            height: 200px; 
            object-fit: contain;
            border-radius: 8px; 
            margin-bottom: 15px;
            background-color: #f8f8f8;
            padding: 5px;
        }
        .name { 
            font-size: 1.5em; 
            font-weight: bold; 
            color: #333; 
            text-transform: uppercase; 
        }
        .price { 
            font-size: 1.2em; 
            color: #e67e22; 
            font-weight: bold; 
        }
        .detail { 
            font-size: 1em; 
            color: #666; 
            font-style: italic; 
        }
        .add-to-cart {
            background: #ff6600; 
            color: #fff; 
            border: none; 
            padding: 10px; 
            border-radius: 5px; 
            cursor: pointer; 
            transition: .3s; 
            margin: 5px; 
        }
        .add-to-cart:hover {
            background: #e65c00; 
            transform: scale(1.05); 
        }

        /* Slider styles */
        .slider-container {
            max-width: 1200px;
            height: 500px;  /* Increased height */
            margin: 20px auto;
            position: relative;
            overflow: hidden;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            background: #fff;
        }

        .slider {
            display: flex;
            transition: transform 0.5s ease-in-out;
            height: 100%;
        }

        .slide {
            min-width: 100%;
            position: relative;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: fill;  /* Changed from cover to fill */
            display: block;
            margin: 0 auto;
        }

        .slide-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 30px;  /* Increased padding */
            background: linear-gradient(transparent, rgba(0,0,0,0.8));  /* Darker gradient */
            color: white;
            text-align: center;
        }

        .slide-content h2 {
            margin: 0;
            font-size: 28px;  /* Increased font size */
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);  /* Added text shadow */
        }

        .slide-content p {
            margin: 15px 0 0;  /* Increased margin */
            font-size: 18px;  /* Increased font size */
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);  /* Added text shadow */
        }

        .slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.9);  /* More opaque background */
            color: #333;
            padding: 20px;  /* Increased padding */
            border: none;
            cursor: pointer;
            border-radius: 50%;
            font-size: 20px;  /* Increased font size */
            width: 60px;  /* Increased width */
            height: 60px;  /* Increased height */
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 10;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);  /* Added shadow */
        }

        .slider-btn:hover {
            background: rgba(255,255,255,1);
            transform: translateY(-50%) scale(1.1);  /* Added scale effect */
        }

        .prev-btn {
            left: 20px;  /* Increased distance from edge */
        }

        .next-btn {
            right: 20px;  /* Increased distance from edge */
        }

        .slider-dots {
            position: absolute;
            bottom: 30px;  /* Increased distance from bottom */
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 12px;  /* Increased gap */
            z-index: 10;
        }

        .dot {
            width: 12px;  /* Increased size */
            height: 12px;  /* Increased size */
            border-radius: 50%;
            background: rgba(255,255,255,0.5);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid rgba(255,255,255,0.8);  /* Added border */
        }

        .dot.active {
            background: white;
            transform: scale(1.2);  /* Added scale effect */
        }
    </style>
</head>
<body>
    <?php
        include "menu.html";
        include "home-produ.html";
        include_once "../shared/connection.php";
    ?>

    <!-- Slider Section -->
    <div class="slider-container">
        <div class="slider">
            <div class="slide">
                <img src="../images/Kids_summer.jpeg" alt="Kids Summer Slam">
                <div class="slide-content">
                    <h2>Kids' Summer Slam</h2>
                    <p>Sports gear for your kids starting ₹149</p>
                </div>
            </div>
            <div class="slide">
                <img src="../images/electronics poster.jpg" alt="Electronics Sale">
                <div class="slide-content">
                    <h2>Electronics Bonanza</h2>
                    <p>Up to 40% off on latest gadgets</p>
                </div>
            </div>
            <div class="slide">
                <img src="../images/fasion.jpg" alt="Fashion Sale">
                <div class="slide-content">
                    <h2>Fashion Festival</h2>
                    <p>Min 50% off on trending styles</p>
                </div>
            </div>
            <div class="slide">
                <img src="../images/grocery.avif" alt="Groceries Sale">
                <div class="slide-content">
                    <h2>Grocery Offer</h2>
                    <p>Min 30% off on Buy 1 Get 1</p>
                </div>
            </div>
        </div>
        
        <button class="slider-btn prev-btn">←</button>
        <button class="slider-btn next-btn">→</button>
        
        <div class="slider-dots">
            <span class="dot active"></span>
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>

    <div class="container">
        <div class="header">
            <h1>Welcome to Our Store</h1>
            <p>Discover amazing products at great prices</p>
        </div>
        
        <?php
            $sql_result = mysqli_query($conn,"select * from product where status = 1");
            while($dbrow = mysqli_fetch_assoc($sql_result)){   
                echo"<div class='pdt-card'>
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
        // Slider functionality
        const slider = document.querySelector('.slider');
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');
        
        let currentSlide = 0;
        const slideCount = slides.length;

        function updateSlider() {
            slider.style.transform = `translateX(-${currentSlide * 100}%)`;
            
            // Update dots
            dots.forEach(dot => dot.classList.remove('active'));
            dots[currentSlide].classList.add('active');
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slideCount;
            updateSlider();
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + slideCount) % slideCount;
            updateSlider();
        }

        // Event listeners
        nextBtn.addEventListener('click', nextSlide);
        prevBtn.addEventListener('click', prevSlide);

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentSlide = index;
                updateSlider();
            });
        });

        // Auto slide every 5 seconds
        setInterval(nextSlide, 5000);
    </script>

    <?php include "footer.html"; ?>
</body>
</html>


