<?php
session_start();
include("includes/db.php");
include("nav.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Prepare cart summary
$total = 0;
$itemCount = 0;
$cartDetails = [];

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $qty) {
        $product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();
        $subtotal = $product['price'] * $qty;
        $total += $subtotal;
        $itemCount += $qty;
        $cartDetails[] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'qty' => $qty,
            'subtotal' => $subtotal
        ];
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['customer_name']  = $_POST['name'];
    $_SESSION['phone']          = $_POST['phone'];
    $_SESSION['address']        = $_POST['address'];
    $_SESSION['order_total']    = $_POST['order_total']; // Or calculate from cart
    $_SESSION['order_id']       = rand(100000, 999999);  // Random order ID

    header("Location: pay.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['payment_method'])) {
    $name    = $_POST["name"];
    $email   = $_POST["email"];
    $phone   = $_POST["phone"];
    $address = $_POST["address"];
    $payment = $_POST["payment_method"];

    // Check if user exists
    $checkUser = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkUser->bind_param("s", $email);
    $checkUser->execute();
    $result = $checkUser->get_result();

    if ($result->num_rows === 0) {
        $insertUser = $conn->prepare("INSERT INTO users (name, email, phone, address) VALUES (?, ?, ?, ?)");
        $insertUser->bind_param("ssss", $name, $email, $phone, $address);
        $insertUser->execute();
        $user_id = $insertUser->insert_id;
    } else {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];
    }

    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status, payment_method) VALUES (?, ?, 'pending', ?)");
    $stmt->bind_param("ids", $user_id, $total, $payment);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Save order items
    foreach ($_SESSION['cart'] as $id => $qty) {
        $product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();
        $price = $product['price'];
        $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt_item->bind_param("iiid", $order_id, $id, $qty, $price);
        $stmt_item->execute();
    }

    // Store order ID and total in session
    $_SESSION['order_id'] = $order_id;
    $_SESSION['order_total'] = $total;

    // Clear cart
    $_SESSION['cart'] = [];

    // Redirect
    if ($payment === 'ABA') {
        header("Location: pay.php");
    } else {
        header("Location: thank_you.php");
    }
    exit;
}
?>
<!-- HTML FORM CONTINUES FROM HERE AS YOU ALREADY PROVIDED -->


<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .container {
    max-width: 700px;
    margin: auto;
    padding: 20px;
    background: #f0f8ff; /* light blue background instead of pink */
    border-radius: 12px;
    margin-top: 30px;
}

.summary-box {
    background: #e0f0ff; /* lighter blue for summary box */
    padding: 15px;
    margin: 20px 0;
    border-radius: 10px;
}

.btn {
    padding: 12px 20px;
    background-color: #007bff; /* blue */
    border: none;
    color: white;
    font-weight: bold;
    cursor: pointer;
    border-radius: 8px;
    transition: background-color 0.3s ease;
}

.btn:hover {
    background-color: #0056b3;
}

/* Payment Modal */
#paymentModal {
    display: none;
    position: fixed;
    top: 20%;
    left: 50%;
    transform: translateX(-50%);
    background: white;
    border: 2px solid #d6e9ff;
    padding: 30px;
    border-radius: 12px;
    z-index: 999;
}

/* Payment Buttons */
#paymentModal h3 {
    text-align: center;
    color: #007bff;
}

.aba-btn {
    background:rgb(6, 67, 133);
    color: white;
}

.cod-btn {
    background:rgb(120, 3, 136);
    color: white;
}

    </style>
</head>
<body>

<div class="container">
    <h2>Checkout</h2>

    <form id="checkoutForm" method="post">
        <label>Full Name</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Phone</label>
        <input type="text" name="phone" required>

         <label>Address</label>
        <textarea name="address" required placeholder="#7, Street 269, Sangkat, Khan, Province"></textarea>

        <!-- Cart Summary -->
        <div class="summary-box">
            <h3>🛒 Order Summary</h3>
            <?php foreach ($cartDetails as $item): ?>
                <p><?= htmlspecialchars($item['name']) ?> x<?= $item['qty'] ?> - $<?= number_format($item['subtotal'], 2) ?></p>
            <?php endforeach; ?>
            <hr>
            <strong>Total: $<?= number_format($total, 2) ?></strong>
        </div>

        <input type="hidden" name="payment_method" id="payment_method" value="">
        <button type="button" class="btn" id="placeOrderBtn">Place Order</button>
    </form>
</div>

<!-- Overlay background -->
<div id="paymentOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; backdrop-filter: blur(3px); background: rgba(0,0,0,0.3); z-index:998;"></div>

<!-- Modern Modal -->
<div id="paymentModal">
    <h3 style="text-align:center; color:#ff69b4;">Choose Payment Option</h3>
<div style="display:flex; flex-direction:column; gap:30px; justify-content:center; margin-top:35px;">
    <button class="aba-btn" onclick="selectPayment('ABA')">💳 Pay with ABA</button>
    <button class="cod-btn" onclick="selectPayment('COD')">🚚 Cash on Delivery</button>
    <button onclick="closePaymentModal()" style="background:#ccc; color:#333; border-radius:10px; padding:10px; border:none;">Cancel</button>
</div>

</div>

<script>
    document.getElementById("placeOrderBtn").addEventListener("click", function() {
        document.getElementById("paymentModal").style.display = "block";
        document.getElementById("paymentOverlay").style.display = "block";
    });

    function selectPayment(method) {
        document.getElementById("payment_method").value = method;
        document.getElementById("checkoutForm").submit();
    }
    function closePaymentModal() {
    document.getElementById("paymentModal").style.display = "none";
    document.getElementById("paymentOverlay").style.display = "none";
}
 
</script>

</body>
</html>
