<?php
// starting the session
session_start();

// including the database connection file
include "../shared/connection.php";

// ------------------- Function to Show the Edit Form -------------------
function showEditForm($product) {
    // This function prints an HTML form with the product's current info
    // User can update name, price, description, and also choose new image

    echo <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <title>Edit Product</title>
        <style>
            /* Simple styling for the popup form */
            body {
                font-family: Arial, sans-serif;
                background: rgba(0,0,0,0.5); /* semi-transparent background */
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .edit-form {
                background: white;
                padding: 20px;
                border-radius: 8px;
                width: 400px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            }
            input, textarea {
                width: 100%;
                padding: 10px;
                margin: 8px 0;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            textarea { height: 100px; }
            .current-image {
                max-width: 100%;
                height: auto;
                margin: 10px 0;
            }
            .buttons {
                margin-top: 15px;
                text-align: right;
            }
            button {
                padding: 8px 15px;
                margin-left: 10px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                color: white;
            }
            .save-btn { background: #4CAF50; }
            .cancel-btn { background: #f44336; }
        </style>
    </head>
    <body>
        <div class="edit-form">
            <h2>Edit Product</h2>
            <!-- Form to update product details -->
            <form action="edititem.php" method="post" enctype="multipart/form-data">
                <!-- hidden field to keep product id -->
                <input type="hidden" name="pid" value="{$product['pid']}">
                
                <!-- input fields for editing -->
                <label>Product Name:</label>
                <input type="text" name="name" value="{$product['name']}" required>
                
                <label>Price:</label>
                <input type="number" name="price" value="{$product['price']}" required>
                
                <label>Description:</label>
                <textarea name="detail" required>{$product['detail']}</textarea>
                
                <label>Current Image:</label>
                <img src="{$product['impath']}" class="current-image">
                
                <label>Upload New Image (optional):</label>
                <input type="file" name="photo" accept="image/*">
                
                <!-- buttons for cancel and save -->
                <div class="buttons">
                    <button type="button" class="cancel-btn" onclick="window.history.back()">Cancel</button>
                    <button type="submit" class="save-btn">Save Changes</button>
                </div>
            </form>
        </div>
    </body>
    </html>
HTML;
}

// ------------------- GET Request: Load Product to Edit -------------------
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['pid'])) {
    // getting product id from URL
    $pid = intval($_GET['pid']);

    // fetching product details from database
    $result = mysqli_query($conn, "SELECT * FROM product WHERE pid=$pid");

    // if product is not found
    if (!$result || !($product = mysqli_fetch_assoc($result))) {
        die('Product not found');
    }

    // show the edit form with current product details
    showEditForm($product);
    exit();
}

// ------------------- POST Request: Save Edited Product -------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pid'])) {
    // collecting form data
    $pid = intval($_POST['pid']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $detail = mysqli_real_escape_string($conn, $_POST['detail']);

    // creating an array of values to update
    $updates = ["name='$name'", "price='$price'", "detail='$detail'"];
    
    // checking if new image is uploaded
    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "../shared/images/";
        $file_name = basename($_FILES['photo']['name']);
        $target_file = $target_dir . $file_name;

        // checking if uploaded file is an image
        if (getimagesize($_FILES['photo']['tmp_name']) === false) {
            die("Error: File is not an image");
        }

        // moving image to target folder
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            $updates[] = "impath='$target_file'";
        } else {
            die("Error uploading image");
        }
    }

    // making update query with all changed fields
    $update_query = "UPDATE product SET " . implode(", ", $updates) . " WHERE pid=$pid";

    // running the update query
    if (mysqli_query($conn, $update_query)) {
        // if update successful, alert and go back
        echo "<script>alert('Product updated successfully!'); window.location.href = 'view.php';</script>";
    } else {
        // if any error happens
        die("Error updating product: " . mysqli_error($conn));
    }
}
?>
