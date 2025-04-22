<?php
// Include the database connection file
include_once "../shared/connection.php";

// Function to check if a table exists, show its structure, and display a sample row
function check_table($conn, $table_name) {
    // Print the table name being checked
    echo "<h3>$table_name Table:</h3>";
    
    // Check if the table exists in the database
    $exists = mysqli_num_rows(mysqli_query($conn, "SHOW TABLES LIKE '$table_name'")) > 0;

    // Print whether the table exists or not
    echo $exists ? "Table exists<br>" : "Table does not exist<br>";
    
    // If the table exists, show its structure and a sample record
    if ($exists) {
        // Display the structure of the table (column names, types, etc.)
        echo "<pre>";
        $structure = mysqli_query($conn, "DESCRIBE $table_name");
        while($row = mysqli_fetch_assoc($structure)) {
            print_r($row); // Print each column's structure
        }
        echo "</pre>";
        
        // Display a sample data row from the table
        echo "<h4>Sample Data:</h4><pre>";
        if ($row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM $table_name LIMIT 1"))) {
            print_r($row); // Print one row of sample data
        }
        echo "</pre>";
    }
}

// Main header for the page
echo "<h2>Database Tables Check</h2>";

// Check the structure and sample data of the 'purchase_history' table
check_table($conn, 'purchase_history');

// Check the structure and sample data of the 'refunds' table
check_table($conn, 'refunds');
?>
