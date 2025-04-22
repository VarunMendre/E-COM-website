<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in; if not, block access
if (!isset($_SESSION['userid'])) {                
    die("Error: Unauthorized access.");
}

// Include the database connection file
include "../shared/connection.php";

// Check if a cart ID is passed via the URL (GET method)
if (!isset($_GET['cartid'])) {
    die("Error: Missing cart ID.");
}

// Sanitize and store the cart ID and user ID as integers
$cartid = (int)$_GET['cartid'];        // Cart ID from the URL
$userid = (int)$_SESSION['userid'];    // User ID from session

// Debugging line – shows the values of cartid and userid
var_dump($cartid, $userid); // You can remove this after testing

// Prepare a SQL statement to update the cart item status to 0 (deleted)
// Only update if the cart item belongs to the logged-in user
$stmt = mysqli_prepare($conn, "UPDATE cart SET status = 0 WHERE cartid = ? AND userid = ?");
if (!$stmt) {
    die("Error preparing statement: " . mysqli_error($conn));
}

// Bind the parameters (cartid and userid) to the prepared statement
if (!mysqli_stmt_bind_param($stmt, "ii", $cartid, $userid)) {
    die("Error binding parameters: " . mysqli_stmt_error($stmt));
}

// Execute the prepared SQL statement
if (!mysqli_stmt_execute($stmt)) {
    die("Error executing statement: " . mysqli_stmt_error($stmt));
}

// Check if any rows were affected (i.e., update successful)
if (mysqli_stmt_affected_rows($stmt) > 0) {  
    echo "Item Deleted Successfully!";
    // Redirect to the viewcart page
    header("Location: viewcart.php");
    exit();
} else {
    // If no rows were affected, something went wrong
    echo "Error: Item not deleted!";
}

// Close the prepared statement and database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
