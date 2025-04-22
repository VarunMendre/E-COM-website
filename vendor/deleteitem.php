<?php

// connecting to the database file
include_once "../shared/connection.php";

// checking if 'pid' is present in the URL (like view.php?pid=3)
if (isset($_GET['pid'])) {
    // getting the product id from the URL
    $pid = $_GET['pid'];
    
    // updating the product status to 0 (means deleted or inactive)
    $status = mysqli_query($conn, "UPDATE product SET status = 0 WHERE pid = '$pid'");
    
    // if update is successful
    if ($status) {
        // showing alert box and redirecting to view.php page
        echo "<script>
            alert('Product deleted successfully!');
            window.location.href='view.php';
      </script>";
    } else {
        // if something went wrong while updating
        echo "<script>alert('Error deleting product!'); window.location.href='view.php';</script>";
    }
} else {
    // if product id is not given in the URL
    echo "<script>alert('Invalid product ID!'); window.location.href='view.php';</script>";
}
?>
