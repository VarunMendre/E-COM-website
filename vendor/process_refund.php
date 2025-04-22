<?php
session_start(); // Start the session
include_once "../shared/connection.php"; // Include the database connection

// Check if the request is a POST request and required data is set
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['refund_id'], $_POST['action'])) {
    $refund_id = $_POST['refund_id']; // Get refund ID from form
    $action = $_POST['action'];       // Get action (approve/reject) from form
    
    // Validate action value
    if ($action == 'approve' || $action == 'reject') {
        $status = ($action == 'approve') ? 'approved' : 'rejected'; // Set new status
        
        // Fetch refund details from the database
        $stmt = mysqli_prepare($conn, "SELECT * FROM refunds WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $refund_id);
        mysqli_stmt_execute($stmt);
        $refund_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        
        // If refund is approved and data is fetched
        if ($action == 'approve' && $refund_data) {
            // Prepare wildcard string for LIKE query
            $product_like = "%" . $refund_data['products'] . "%";
            
            // Fetch matching purchase history record
            $stmt = mysqli_prepare($conn, "SELECT * FROM purchase_history WHERE userid = ? AND products LIKE ?");
            mysqli_stmt_bind_param($stmt, "is", $refund_data['user_id'], $product_like);
            mysqli_stmt_execute($stmt);
            $purchase_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
            
            if ($purchase_data) {
                // Calculate remaining products after refund using array_diff
                $remaining_products = array_diff(
                    explode(',', $purchase_data['products']), 
                    explode(',', $refund_data['products'])
                );
                
                if ($remaining_products) {
                    // Update purchase history with remaining products
                    $updated_products = implode(',', $remaining_products);
                    $stmt = mysqli_prepare($conn, "UPDATE purchase_history SET products = ? WHERE purchase_id = ?");
                    mysqli_stmt_bind_param($stmt, "si", $updated_products, $purchase_data['purchase_id']);
                } else {
                    // If no products remain, delete the purchase history record
                    $stmt = mysqli_prepare($conn, "DELETE FROM purchase_history WHERE purchase_id = ?");
                    mysqli_stmt_bind_param($stmt, "i", $purchase_data['purchase_id']);
                }
                mysqli_stmt_execute($stmt); // Execute update or delete
            }
        }
        
        // Update the refund request status (approved or rejected)
        $stmt = mysqli_prepare($conn, "UPDATE refunds SET status = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "si", $status, $refund_id);
        mysqli_stmt_execute($stmt);
    }
}

// Redirect back to the refund management page after processing
header("Location: manage_refunds.php");
exit(); 
