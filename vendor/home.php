<?php
    session_start(); // start session to access login data

    // check if user is not logged in OR user is not a vendor
    if (!isset($_SESSION['login_status']) || $_SESSION['login_status'] != true || 
        !isset($_SESSION['user_type']) || $_SESSION['user_type'] != "Vendor") {
        
        // if not logged in or not a vendor, send user to login page
        header("location:../shared/login.html");
        exit(); // stop running the rest of the code
    }

    // include the vendor menu (navigation bar)
    include "menu.html";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Vendor Home</title>
    <style>
        /* Basic styling for the whole page */
        body {
            font-family: Arial, sans-serif;
            background-color: #eaf4f9; /* light blue background */
            margin: 0;
            padding: 0;
            min-height: 100vh;
            position: relative;
        }

        /* Styling for the top fixed menu */
        .menu-container {
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        /* Main content area starts below the menu */
        .content-container {
            padding-top: 80px; /* leaves space for fixed menu */
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: calc(100vh - 80px);
        }

        /* Heading style */
        h1 {
            color: #004b84;
            font-size: 28px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }

        /* Styling for the product upload form */
        form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 450px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            text-align: center;
            margin: 20px auto;
        }

        /* Style for all input and textarea fields */
        input, textarea {
            padding: 10px;
            border: 1px solid #004b84;
            border-radius: 4px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
        }

        /* Submit button styling */
        button {
            background-color: #004b84;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #003a6a; /* darker shade on hover */
        }

        /* Label and message styles */
        label {
            color: #004b84;
            font-weight: bold;
            text-align: left;
        }

        .success-message {
            color: #004b84;
            font-weight: bold;
            margin-top: 10px;
        }

        .error-message {
            color: #d9534f;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Fixed menu at the top -->
    <div class="menu-container">
        <?php include "menu.html"; ?> <!-- Reusing menu bar again here for layout -->
    </div>
    
    <!-- Main content area -->
    <div class="content-container">
        <h1>Upload Product's</h1>
        
        <!-- Product upload form -->
        <!-- 'multipart/form-data' is needed to allow file/image upload -->
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <!-- Input for product name -->
            <input required type="text" placeholder="Product Name" name="name">
            
            <!-- Input for product price -->
            <input required type="number" placeholder="Product Price" name="price">
            
            <!-- Textarea for product description -->
            <textarea required placeholder="Product Description" name="details" id="" cols="30" rows="5"></textarea>
            
            <!-- File input for product image, only allowing jpg/png/gif -->
            <input required type="file" accept="image/jpeg, image/png, image/gif" name="pdt-img" >
            
            <!-- Submit button -->
            <button>Upload Product</button>
        </form>
    </div>
</body>
</html>
