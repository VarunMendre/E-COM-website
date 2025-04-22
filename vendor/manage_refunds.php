<?php
session_start();
include_once "../shared/connection.php"; // Connect to the database

// Fetch all refund requests from the 'refunds' table, latest first
$sql = "SELECT * FROM refunds ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Requests</title>
    
    <!-- Bootstrap CSS for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styling for layout and appearance -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .request-box {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            padding: 20px;
        }
        .pending { color: #ffc107; font-weight: bold; }
        .approved { color: #28a745; font-weight: bold; }
        .rejected { color: #dc3545; font-weight: bold; }
        .btn-action {
            margin-right: 10px;
        }
    </style>
</head>
<body>

    <!-- Main container -->
    <div class="container mt-4">

        <!-- Header section with title and back button -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Refund Requests</h2>
            <a href="view.php" class="btn btn-secondary">Back to Products</a>
        </div>

        <!-- If there are refund requests, display them -->
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="request-box">
                    <div class="row">
                        <!-- Left section with request info -->
                        <div class="col-md-6">
                            <h5>Request #<?php echo $row['id']; ?></h5>
                            <p><strong>Customer ID:</strong> <?php echo htmlspecialchars($row['user_id']); ?></p>
                            <p><strong>Products:</strong> <?php echo htmlspecialchars($row['products']); ?></p>
                            <p><strong>Status:</strong> 
                                <!-- Show status with color class (pending/approved/rejected) -->
                                <span class="<?php echo strtolower($row['status']); ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </p>
                        </div>

                        <!-- Right section with reason and actions -->
                        <div class="col-md-6">
                            <p><strong>Reason for Return:</strong></p>
                            <!-- Display reason with line breaks -->
                            <p><?php echo nl2br(htmlspecialchars($row['reason'])); ?></p>

                            <p><strong>Requested on:</strong> <?php echo date('F j, Y g:i A', strtotime($row['created_at'])); ?></p>

                            <!-- Only show Approve/Reject buttons if request is still pending -->
                            <?php if($row['status'] == 'pending'): ?>
                                <div class="mt-3">
                                    <!-- Approve Form -->
                                    <form action="process_refund.php" method="POST" class="d-inline">
                                        <input type="hidden" name="refund_id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="btn btn-success btn-action">Approve</button>
                                    </form>

                                    <!-- Reject Form -->
                                    <form action="process_refund.php" method="POST" class="d-inline">
                                        <input type="hidden" name="refund_id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="btn btn-danger btn-action">Reject</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <!-- If no refund requests found -->
            <div class="alert alert-info">No refund requests found.</div>
        <?php endif; ?>
    </div>
</body>
</html>
