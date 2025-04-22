<?php

    session_start();

    if (!isset($_SESSION['userid'])) {                
        die("Error: Unauthorized access.");
    }
    include "home-produ.html";
    include "menu.html";
    include "../shared/connection.php";

    // Get cart items
    $stmt = $conn->prepare("SELECT c.cartid, p.pid, p.name, p.price, p.impath, p.detail 
                           FROM cart c 
                           INNER JOIN product p ON c.pid = p.pid 
                           WHERE c.userid = ? AND c.status = 1");
    $stmt->bind_param("i", $_SESSION['userid']);
    $stmt->execute();
    $result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cart</title>
    <style>
        /* styling for 'Place Order' button */
        .place-order-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #28a745;
            color: white;
            border: none;
            padding: 15px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }
        .place-order-btn:hover {
            background-color: #218838;
        }

        /* Add min-height to ensure footer stays at bottom */
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .content-wrapper {
            flex: 1;
            padding-bottom: 60px; /* Space for the Place Order button */
        }

        /* Push footer to bottom */
        footer {
            margin-top: auto;
        }

        /* styling for 'Remove' button */
        button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 15px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        button:hover {
            background-color: #c82333;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <?php
        while($dbrow = mysqli_fetch_assoc($result)){
            echo"<div class = 'pdt-card'>
             
            <div class = 'name'>$dbrow[name]</div>
            <div class = 'price'>$dbrow[price]</div>
            <img class = 'pdt-img' src ='$dbrow[impath]'>
            <div class = 'detail'>$dbrow[detail]</div>

            <div class=''>
                <a href='deletecart.php?cartid=$dbrow[cartid]'>
                    <button class=''>Remove</button>
                </a>
            </div>
        </div>";
        }
        ?>
    </div>

    <!-- Floating 'place order' button -->
    <button class="place-order-btn" onclick="location.href='trackorder.php'">Place Order</button>

    <?php
        include "footer.html"; //footer of the page 
    ?>
</body>
</html>