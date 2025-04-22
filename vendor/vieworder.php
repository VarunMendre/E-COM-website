<?php
    // Start the session
    session_start();

    // Include the database connection file
    include_once "../shared/connection.php";

    // Check if the user is logged in and is a Vendor
    if(!isset($_SESSION['login_status']) || $_SESSION['login_status'] != true || $_SESSION['user_type'] != "Vendor") {
        // If not, redirect to the login page
        header("location:../shared/login.html");
        exit();
    }

    // Fetch all purchase history records, ordered by most recent first
    $sql_result = mysqli_query($conn, "SELECT * FROM purchase_history ORDER BY purchase_date DESC");

    // If query fails, show error
    if(!$sql_result) {
        die("Error in SQL");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags and title -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>

    <!-- Internal CSS styling -->
    <style>
        /* Basic page styles */
        body {
            background-color: #f5f5f5;
            padding: 20px;
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        /* Back button */
        .back-btn {
            background-color: #4a90e2;
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }

        /* Header section */
        .header {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Individual order block */
        .order-block {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-left: 4px solid #4a90e2;
        }

        /* Order header (top section) */
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        /* User information block */
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Styling for username */
        .user-name {
            font-weight: bold;
        }

        /* Styling for user ID and purchase date */
        .user-id, .purchase-date {
            color: #666;
            background-color: #f0f0f0;
            padding: 3px 8px;
            border-radius: 3px;
        }

        /* Styling for purchase total price */
        .purchase-total {
            color: #fff;
            font-weight: bold;
            background-color: #4a90e2;
            padding: 5px 10px;
            border-radius: 3px;
        }

        /* Styling for individual product item */
        .product-item {
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 3px;
            margin-bottom: 5px;
        }

        /* Search box styling */
        .search-box {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        /* Display message when no orders are found */
        .no-orders {
            text-align: center;
            padding: 30px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Responsive layout for smaller screens */
        @media (max-width: 768px) {
            .order-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .user-info {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Navigation back button -->
        <a href="home.php" class="back-btn">← Back to Home</a>
        
        <!-- Page header -->
        <div class="header">
            <h1>All Orders</h1>
        </div>

        <!-- Search box input -->
        <input type="text" class="search-box" placeholder="Search by username or product..." id="searchInput">

        <!-- Check if any orders exist -->
        <?php if(mysqli_num_rows($sql_result) > 0) { 
            // Loop through each order
            while($row = mysqli_fetch_assoc($sql_result)) { ?>
                <div class="order-block">
                    <div class="order-header">
                        <!-- Display user info -->
                        <div class="user-info">
                            <div class="user-name"><?php echo htmlspecialchars($row['username']); ?></div>
                            <div class="user-id">ID: <?php echo $row['userid']; ?></div>
                        </div>

                        <!-- Display purchase date -->
                        <div class="purchase-date">
                            <?php echo date('F d, Y', strtotime($row['purchase_date'])); ?>
                        </div>

                        <!-- Display total purchase amount -->
                        <div class="purchase-total">
                            Total: ₹<?php echo number_format($row['total_price'], 2); ?>
                        </div>
                    </div>
                    
                    <!-- Display list of purchased products -->
                    <div class="products-list">
                        <?php foreach(explode(', ', $row['products']) as $product) { ?>
                            <div class="product-item">
                                <?php echo htmlspecialchars($product); ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } 
        } else { ?>
            <!-- Show message if no orders found -->
            <div class="no-orders">
                <h2>No Orders Found</h2>
                <p>No users have made any purchases yet.</p>
            </div>
        <?php } ?>
    </div>

    <!-- JavaScript for real-time search functionality -->
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchText = this.value.toLowerCase(); // Get input text
            document.querySelectorAll('.order-block').forEach(block => {
                const username = block.querySelector('.user-name').textContent.toLowerCase(); // Username text
                const products = block.querySelector('.products-list').textContent.toLowerCase(); // Products text
                // Show/hide block based on match
                block.style.display = username.includes(searchText) || products.includes(searchText) ? 'block' : 'none';
            });
        });
    </script>
</body>
</html>
