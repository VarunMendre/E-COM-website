<?php
// Start the session to access user data
session_start();

// Include database connection
include "../shared/connection.php";

// Ensure user is logged in
if (!isset($_SESSION['userid'])) {
    die("Unauthorized access");
}

$userid = $_SESSION['userid'];
$username = $_SESSION['username'] ?? 'Guest';

// Function to calculate total value of items in the cart
function calculateCartTotal($userid, $conn) {
    $stmt = $conn->prepare("SELECT SUM(product.price) FROM cart 
                          INNER JOIN product ON cart.pid = product.pid 
                          WHERE cart.userid = ? AND cart.status = 1");
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    return $stmt->get_result()->fetch_row()[0] ?? 0;
}

// Fetch total price
$total_price = calculateCartTotal($userid, $conn);

// Fetch cart items
$stmt = $conn->prepare("SELECT product.name, product.price FROM cart 
                       INNER JOIN product ON cart.pid = product.pid 
                       WHERE cart.userid = ? AND cart.status = 1");
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- HTML Boilerplate -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Order</title>

    <!-- Styling for the page -->
    <style>
        /* Page styling */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
        }

        /* Main container styling */
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 600px;
            width: 80%;
        }

        /* Headings and table styling */
        h2, h3 { color: #333; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td { border: 1px solid #ddd; }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th { background-color: #f8f8f8; }

        /* Form input and button styling */
        input[type="text"], button {
            margin-top: 10px;
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #28a745;
            color: white;
            cursor: pointer;
            border: none;
        }
        button:hover {
            background-color: #218838;
        }

        input[type="radio"] { margin: 10px; }

        /* View Cart button styling */
        .home-bt {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            position: absolute;
            top: 10px;
            left: 10px;
            border-radius: 5px;
            border: none;
            font-size: 16px;
            cursor: pointer;
        }
        .home-bt:hover {
            background-color: #0056b3;
        }

        /* Modal popup styling for online payment */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0; top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
            overflow-y: auto;
        }
        .modal-content {
            background: #fff;
            margin: 5% auto;
            padding: 20px;
            width: 50%;
            border-radius: 10px;
            transform: translateY(-50%);
            top: 50%;
            position: relative;
        }

        .close {
            float: right;
            font-size: 28px;
            color: #aaa;
            font-weight: bold;
            cursor: pointer;
        }

        /* Payment form styling inside modal */
        .payment-form {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        .payment-form input, .payment-form select {
            width: 100%;
            margin: 8px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .card-details {
            display: flex;
            gap: 10px;
        }
        .card-details input {
            width: 50%;
        }
        .process-payment-btn {
            background-color: #28a745;
            color: white;
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .process-payment-btn:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>

    <!-- Go to Cart button -->
    <a href="viewcart.php"><button class="home-bt">View Cart</button></a>

    <!-- Main content box -->
    <div class="container">
        <h2>Track Your Order</h2>
        <p>Welcome, <strong><?php echo htmlspecialchars($username); ?></strong></p>

        <!-- Product list -->
        <h3>Selected Products</h3>
        <table>
            <tr><th>Product Name</th><th>Price</th></tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td>₹<?php echo number_format($row['price']); ?></td>
                </tr>
            <?php } ?>
        </table>

        <!-- Total price and promo code -->
        <p><strong>Total Amount: ₹<span id="totalAmount"><?php echo number_format($total_price); ?></span></strong></p>

        <h3>Apply Promo Code</h3>
        <input type="text" id="promoCode" placeholder="Enter promo code">
        <button onclick="applyPromoCode()">Apply</button>

        <!-- Payment options -->
        <h3>Select Payment Method</h3>
        <input type="radio" name="payment" value="cod" id="cod"> Cash on Delivery (COD) <br>
        <input type="radio" name="payment" value="online" id="online" onclick="openPaymentPopup()"> Online Payment (5% Off)
        <br><br>
        <button onclick="confirmPayment()">Confirm Payment</button>
    </div>

    <!-- Online Payment Modal -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closePaymentPopup()">&times;</span>
            <h3>Select Payment Method</h3>

            <!-- Online payment options -->
            <input type="radio" name="onlinePayment" value="upi" onclick="showPaymentForm('upi')"> UPI Payment <br>
            <input type="radio" name="onlinePayment" value="netbanking" onclick="showPaymentForm('netbanking')"> Net Banking <br>
            <input type="radio" name="onlinePayment" value="card" onclick="showPaymentForm('card')"> Credit/Debit Card <br>

            <!-- Payment input forms -->
            <div id="upiForm" class="payment-form" style="display: none;">
                <h4>UPI Payment Details</h4>
                <input type="text" placeholder="Enter UPI ID" id="upiId">
                <input type="password" placeholder="Enter UPI PIN" id="upiPin">
            </div>

            <div id="netbankingForm" class="payment-form" style="display: none;">
                <h4>Net Banking Details</h4>
                <input type="text" placeholder="Enter Bank Account Number" id="accountNumber">
                <input type="password" placeholder="Enter Net Banking Password" id="netBankingPassword">
                <select id="bankName">
                    <option value="">Select Bank</option>
                    <option value="sbi">State Bank of India</option>
                    <option value="hdfc">HDFC Bank</option>
                    <option value="icici">ICICI Bank</option>
                    <option value="axis">Axis Bank</option>
                </select>
            </div>

            <div id="cardForm" class="payment-form" style="display: none;">
                <h4>Card Details</h4>
                <input type="text" placeholder="Card Number" id="cardNumber">
                <input type="text" placeholder="Card Holder Name" id="cardName">
                <div class="card-details">
                    <input type="text" placeholder="MM/YY" id="cardExpiry">
                    <input type="password" placeholder="CVV" id="cardCvv">
                </div>
            </div>

            <button onclick="processPayment()" class="process-payment-btn">Proceed to Pay</button>
        </div>
    </div>

    <!-- JavaScript for promo and payment logic -->
    <script>
        let originalPrice = <?php echo $total_price; ?>; 
        let finalPrice = originalPrice;
        let selectedPaymentMethod = null;

        // Apply 10% promo code discount
        function applyPromoCode() {
            let promoCode = document.getElementById("promoCode").value;
            if (promoCode.length === 5) {
                finalPrice = originalPrice * 0.90;
                document.getElementById("totalAmount").innerText = finalPrice.toFixed(2);
                alert("Promo Code Applied! 10% Discount Given.");
            } else {
                alert("Invalid Promo Code! Must be exactly 5 characters.");
            }
        }

        // Open online payment modal and apply 5% discount
        function openPaymentPopup() {
            finalPrice = originalPrice * 0.95;
            document.getElementById("totalAmount").innerText = finalPrice.toFixed(2);
            document.getElementById("paymentModal").style.display = "block";
        }

        function closePaymentPopup() {
            document.getElementById("paymentModal").style.display = "none";
        }

        // Show the form based on payment method selected
        function showPaymentForm(method) {
            document.getElementById("upiForm").style.display = "none";
            document.getElementById("netbankingForm").style.display = "none";
            document.getElementById("cardForm").style.display = "none";

            if (method === 'upi') {
                document.getElementById("upiForm").style.display = "block";
            } else if (method === 'netbanking') {
                document.getElementById("netbankingForm").style.display = "block";
            } else if (method === 'card') {
                document.getElementById("cardForm").style.display = "block";
            }
            selectedPaymentMethod = method;
        }

        // Process payment after validating form
        function processPayment() {
            let isValid = true;
            let message = "";

            // Check selected method and required inputs
            if (selectedPaymentMethod === 'upi') {
                if (!document.getElementById("upiId").value || !document.getElementById("upiPin").value) {
                    isValid = false;
                    message = "Please enter UPI ID and PIN";
                }
            } else if (selectedPaymentMethod === 'netbanking') {
                if (!document.getElementById("accountNumber").value || 
                    !document.getElementById("netBankingPassword").value ||
                    !document.getElementById("bankName").value) {
                    isValid = false;
                    message = "Please enter all net banking details";
                }
            } else if (selectedPaymentMethod === 'card') {
                if (!document.getElementById("cardNumber").value || 
                    !document.getElementById("cardName").value ||
                    !document.getElementById("cardExpiry").value ||
                    !document.getElementById("cardCvv").value) {
                    isValid = false;
                    message = "Please enter all card details";
                }
            }

            if (!isValid) {
                alert(message);
                return;
            }

            // Simulate processing delay
            const processBtn = document.querySelector('.process-payment-btn');
            const originalText = processBtn.textContent;
            processBtn.textContent = 'Processing...';
            processBtn.disabled = true;

            setTimeout(() => {
                processBtn.textContent = originalText;
                processBtn.disabled = false;
                alert("Payment processed successfully!");
                closePaymentPopup();
                document.getElementById("online").checked = true;
            }, 3000);
        }

        // Final confirmation and send data to server
        function confirmPayment() {
            if (!document.getElementById("cod").checked && !document.getElementById("online").checked) {
                alert("Please select a payment method.");
                return;
            }

            if (document.getElementById("online").checked && !selectedPaymentMethod) {
                alert("Please complete the online payment process.");
                return;
            }

            // Get all products listed in the table
            const productRows = document.querySelectorAll('table tr:not(:first-child)');
            const products = Array.from(productRows).map(row => row.querySelector('td:first-child').textContent);

            const totalAmount = finalPrice || originalPrice;

            // Send order data to server
            fetch('update_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    products: products,
                    total_price: totalAmount
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    alert('Order placed successfully!');
                    window.location.href = 'home.php';
                } else {
                    alert('Error placing order: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error placing order. Please try again.');
            });
        }
    </script>
</body>
</html>
