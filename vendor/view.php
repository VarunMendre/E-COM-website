<?php
    session_start();

    // Check if user is logged in
    if (!isset($_SESSION['login_status'])) {
        header("location:../shared/login.html"); // Redirect to login page if not logged in
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Vendor View</title>
    <style>
        /* General Page Styling */
        body { 
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        /* Sticky top menu bar */
        .menu-container {
            background-color: white;
            box-shadow: 0 2px 5px #ccc;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        /* Page header styling */
        .page-header {
            text-align: center;
            padding: 20px;
            background-color: #6c5ce7;
            color: white;
            margin-bottom: 20px;
        }

        /* Container for all product cards */
        .products-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
            justify-content: center;
        }

        /* Individual product card styling */
        .pdt-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px #ccc;
            width: 300px;
            overflow: hidden;
        }

        /* Product image styling */
        .pdt-img-container {
            height: 200px;
            padding: 10px;
        }

        .pdt-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Product content */
        .pdt-content {
            padding: 15px;
        }

        .name {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .price {
            color: #6c5ce7;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .detail {
            color: #666;
            margin-bottom: 15px;
        }

        /* Button container and styling */
        .btn-container {
            display: flex;
            gap: 10px;
            padding: 0 15px 15px;
        }

        .btn-custom {
            background-color: #6c5ce7;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        .btn-custom:hover {
            background-color: #5b4bc4;
        }

        /* Empty state styling when no products exist */
        .empty-state {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 8px;
            margin: 20px auto;
            max-width: 500px;
        }

        /* Responsive styling */
        @media (max-width: 768px) {
            .pdt-card {
                width: 100%;
                max-width: 350px;
            }
        }
    </style>
</head>
<body>

    <!-- Top menu (navigation) -->
    <div class="menu-container">
        <?php include "menu.html"; ?>
    </div>
    
    <!-- Page header -->
    <div class="page-header">
        <h1>Product Management</h1>
    </div>

<?php
    include_once "../shared/connection.php";

    // Fetch all active (status = 1) products from the database
    $sql_result = mysqli_query($conn, "SELECT * FROM product WHERE status = 1");

    echo "<div class='products-container'>";

    $has_products = false;

    // Loop through each product and render as card
    while ($dbrow = mysqli_fetch_assoc($sql_result)) {
        $has_products = true;

        echo "<div class='pdt-card'>
            <div class='pdt-img-container'>
                <img class='pdt-img' src='{$dbrow['impath']}'>
            </div>
            <div class='pdt-content'>
                <div class='name'>{$dbrow['name']}</div>
                <div class='price'>₹{$dbrow['price']}</div>
                <div class='detail'>{$dbrow['detail']}</div>
                <div class='btn-container'>
                    <!-- Remove product -->
                    <a href='deleteitem.php?pid={$dbrow['pid']}'>
                        <button class='btn-custom'>Remove</button>
                    </a>
                    <!-- Edit product -->
                    <a href='edititem.php?pid={$dbrow['pid']}'>
                        <button class='btn-custom'>Edit</button>
                    </a>
                </div>
            </div>
        </div>";
    }

    // Show message if no products are available
    if (!$has_products) {
        echo "<div class='empty-state'>
            <h2>No Products Available</h2>
            <p>You haven't added any products yet. Click the 'Upload Product' button to get started.</p>
        </div>";
    }

    echo "</div>"; // End of products container
?>

<!-- Include footer -->
<?php include "../customer/footer.html"; ?>

</body>
</html>
