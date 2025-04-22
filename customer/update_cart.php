<?php
session_start(); // Start the session to access session variables

include "../shared/connection.php"; // Include the database connection script

// Check if the user is logged in and has a valid session
if (!isset($_SESSION['login_status']) || !isset($_SESSION['userid'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit(); // Exit if the user is not logged in
}

// Get JSON data from the request body and decode it into an associative array
$data = json_decode(file_get_contents('php://input'), true);
$products = $data['products'] ?? [];       // Get products array or default to empty array
$total_price = $data['total_price'] ?? 0;  // Get total price or default to 0

// Validate input: Ensure products and total_price are not empty
if (empty($products) || empty($total_price)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data received']);
    exit(); // Exit if input is invalid
}

$userid = $_SESSION['userid'];       // Get the user ID from session
$username = $_SESSION['username'];   // Get the username from session

// Convert the products array into a comma-separated string
$products_string = implode(', ', $products);

// Prepare the SQL query to insert the purchase data into purchase_history table
$insert_query = "INSERT INTO purchase_history (userid, username, products, total_price) 
                VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $insert_query); // Prepare the SQL statement
mysqli_stmt_bind_param($stmt, "issd", $userid, $username, $products_string, $total_price); // Bind parameters

// Execute the insert query
if (mysqli_stmt_execute($stmt)) {
    // If insert is successful, update the cart items' status to 2 (purchased)
    $update_cart = "UPDATE cart SET status = 2 WHERE userid = ? AND status = 1";
    $stmt = mysqli_prepare($conn, $update_cart); // Prepare the update statement
    mysqli_stmt_bind_param($stmt, "i", $userid); // Bind user ID
    mysqli_stmt_execute($stmt); // Execute the update

    // Return success response
    echo json_encode(['status' => 'success', 'message' => 'Purchase history updated successfully']);
} else {
    // If insert fails, return error response
    echo json_encode(['status' => 'error', 'message' => 'Failed to update purchase history']);
}

// Close the prepared statement and database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
