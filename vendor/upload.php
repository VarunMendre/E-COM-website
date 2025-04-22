<?php
session_start(); // Start the session to access session variables

// Get the temporary file path of the uploaded image
$source_path = $_FILES['pdt-img']['tmp_name']; 

// Define the target path where the image will be stored permanently
$target = "../shared/images/" . $_FILES['pdt-img']['name'];

// Move the uploaded file from temp location to the target location
move_uploaded_file($source_path, $target); // This function actually uploads the file to server

// Include the database connection file
include_once "../shared/connection.php"; 

// Build the SQL query to insert product details into the `product` table
$query = "INSERT INTO product(name, price, detail, impath, owner) 
          VALUES('$_POST[name]', '$_POST[price]', '$_POST[details]', '$target', $_SESSION[userid])";

// Debugging purpose: Print the query to verify
echo $query;

// Execute the query and store the result
$status = mysqli_query($conn, $query);

// Check if the query was successful
if ($status) {
    // Redirect to the view page if product insertion is successful
    header("location:view.php");
} else {
    // Show error message if insertion failed
    echo "<br> Uploadation failed";
}
?>
