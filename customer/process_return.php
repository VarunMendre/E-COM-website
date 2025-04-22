<?php
// Start the session to access session variables
session_start();

// Include the database connection
include_once "../shared/connection.php";

// Check if user is logged in; if not, redirect to login page
if (!isset($_SESSION['userid'])) {
    header("location: ../login.php");
    exit();
}

// Check if the form was submitted using POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['userid'];               // Get the logged-in user ID from session
    $purchase_id = $_POST['purchase_id'];         // Get the purchase ID from form submission
    $reason = $_POST['reason'];                   // Get the reason for return from form

    // Step 1: Fetch the products associated with the given purchase ID
    $sql = "SELECT products FROM purchase_history WHERE purchase_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $purchase_id);   // Bind purchase ID as integer
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);                 // Fetch the product details

    // Step 2: If purchase record is found, proceed with return request
    if ($row) {
        $sql = "INSERT INTO refunds (user_id, purchase_id, products, reason, status, created_at) 
                VALUES (?, ?, ?, ?, 'pending', NOW())";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iiss", $user_id, $purchase_id, $row['products'], $reason);

        // Step 3: Execute insert and set appropriate success or error message
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = "Return request submitted successfully.";
        } else {
            $_SESSION['message'] = "Error submitting return request: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);  // Close the statement
    } else {
        // If no such purchase found
        $_SESSION['message'] = "Purchase not found.";
    }
}

// Step 4: Redirect to the returns page with message in session
header("location: returns.php");
exit();
