<?php
// Start session to access user information
session_start();

// Include the database connection file
include_once "../shared/connection.php";

// Redirect to login if the user is not logged in
if (!isset($_SESSION['userid'])) {
    header("location: ../login.php");
    exit();
}

$user_id = $_SESSION['userid'];  // Get logged-in user's ID from session

// Debugging info - helpful during development
echo "<!-- User ID: $user_id -->";

// Fetch the purchase history of the logged-in user
$sql = "SELECT * FROM purchase_history WHERE userid = '$user_id'";
$result = mysqli_query($conn, $sql);

// Debugging info - query and result details
echo "<!-- SQL Query: $sql -->";
echo "<!-- Result count: " . ($result ? mysqli_num_rows($result) : 'Query failed') . " -->";

// Check if there was a problem with the query
if (!$result) {
    echo "<!-- SQL Error: " . mysqli_error($conn) . " -->";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return & Refund</title>
    <!-- Bootstrap CSS for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <!-- Page title and back to Home button -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Return & Refund</h2>
            <a href="home.php" class="btn btn-primary">Home</a>
        </div>
        
        <!-- Check if there are any purchases to show -->
        <?php if(mysqli_num_rows($result) > 0): ?>
            <!-- Loop through each purchase and display return form -->
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5>Purchase #<?php echo $row['purchase_id']; ?></h5>
                        <p>Total: ₹<?php echo $row['total_price']; ?></p>
                        
                        <!-- Form for submitting a return request -->
                        <form action="process_return.php" method="POST">
                            <!-- Hidden field to pass purchase ID -->
                            <input type="hidden" name="purchase_id" value="<?php echo $row['purchase_id']; ?>">
                            
                            <!-- Checkbox list to select products to return -->
                            <div class="mb-3">
                                <label>Select Products to Return:</label>
                                <?php
                                $products = explode(',', $row['products']); // Convert comma-separated string to array
                                foreach($products as $product): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="return_products[]" value="<?php echo trim($product); ?>">
                                        <label class="form-check-label"><?php echo trim($product); ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Textarea for user to enter reason for return -->
                            <div class="mb-3">
                                <label>Reason for Return:</label>
                                <textarea name="reason" class="form-control" required></textarea>
                            </div>

                            <!-- Submit button -->
                            <button type="submit" class="btn btn-primary">Submit Return Request</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <!-- Message when there are no purchases -->
            <div class="alert alert-info">No purchases found.</div>
        <?php endif; ?>
    </div>
</body>
</html>
