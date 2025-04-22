<?php
// Just printing the received form data to check if it's coming through correctly
echo "Received Values are";
print_r($_POST);

// Connecting to the database (localhost, username, password, database name, port)
include_once "connection.php";

// Hashing the password before storing it in the database (for security using 'password_hash()')
$hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Assigning correct form field names
$user_type = $_POST['usertype']; // Fixing undefined array key issue

// Get the highest vendor status if this is a vendor signup
$status_value = 0;
if($user_type == "Vendor") {
    $max_status_result = mysqli_query($conn, "SELECT MAX(status) as max_status FROM user WHERE user_type = 'Vendor'");
    $max_status_row = mysqli_fetch_assoc($max_status_result);
    $status_value = ($max_status_row['max_status'] ?? 0) + 1;
}

// Inserting the user data into the "user" table (not "users")
$status = mysqli_query($conn, "INSERT INTO user (username, password, mail_id, user_type, status) 
VALUES ('{$_POST['username']}', '$hashed_password', '{$_POST['mail_id']}', '$user_type', $status_value)");

// Checking if data insertion was successful
if ($status) {
    echo "<h1>User SignUp Success</h1>"; // Redirect to login for Success to verify again
    header("location:login.html"); //
    exit(); // 
} else {
    echo "<h1>Error in SignUp</h1>"; // Error message if insertion fails
}
?>
