<?php

// Start the session to store user login status
session_start();
$_SESSION['login_status'] = false; // Default to false (user not logged in)

// Printing the received form data (for debugging)
echo "Received Values are";
print_r($_POST);

// Connecting to the database (localhost, username, password, database, port)

include_once "connection.php";

// Fetching user data from the database using the provided username
$sql_result = mysqli_query($conn, "SELECT * FROM user WHERE username = '{$_POST['username']}'");

print_r($sql_result); // Printing query result for debugging

// use to Fetching user details from the query result
$dbrow = mysqli_fetch_assoc($sql_result);
echo "<br>";
print_r($dbrow); // Printing fetched user data for debugging

// To compare the entered password with the hashed password stored in the database
if ($dbrow && password_verify($_POST['password'], $dbrow['password'])) { 
    echo "<h1>Login Success</h1>";
    $_SESSION['login_status'] = true; // Mark user as logged in
    $_SESSION['userid']=$dbrow['userid'];
    $_SESSION['user_type']=$dbrow['user_type'];
    $_SESSION['username']=$dbrow['username'];

    if ($dbrow["user_type"] == "Vendor") {
        // Redirect to vendor home page
        header("location:../vendor/home.php");
    } else if ($dbrow["user_type"] == "Customer") {
        // Redirect to customer home page
        header("location:../customer/home.php");
    }
} else {
    echo "<h1>Login Failed</h1>"; // If username or password is wrong
}

?>